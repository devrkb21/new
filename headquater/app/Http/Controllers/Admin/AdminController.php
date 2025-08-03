<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingPeriod;
use App\Models\Plan;
use App\Models\Site;
use App\Models\User;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{

    public function dashboard()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $totalSites = Site::count();
        $basicPlan = Plan::where('slug', 'basic')->first();
        $standardPlan = Plan::where('slug', 'standard')->first();
        $basicUsers = $basicPlan ? Site::where('plan_id', $basicPlan->id)->count() : 0;
        $standardUsers = $standardPlan ? Site::where('plan_id', $standardPlan->id)->count() : 0;
        $totalUsers = User::where('is_admin', false)->count();
        $openTickets = Ticket::whereIn('status', ['open', 'in-progress'])->count();
        $dueInvoices = Invoice::where('status', 'due')->count();

        $registrations = Site::query()
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->pluck('count', 'date');
        $chartData = [];
        $chartLabels = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = $date;
            $chartData[] = $registrations->get($date, 0);
        }

        $latestSites = Site::with('plan')->latest()->take(5)->get();
        $latestUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', [
            'totalSites' => $totalSites,
            'basicUsers' => $basicUsers,
            'standardUsers' => $standardUsers,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'latestSites' => $latestSites,
            'latestUsers' => $latestUsers,
            'basicPlanId' => $basicPlan->id ?? null,
            'standardPlanId' => $standardPlan->id ?? null,
            'totalUsers' => $totalUsers,
            'openTickets' => $openTickets,
            'dueInvoices' => $dueInvoices,
        ]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        $query = Site::with('plan', 'user');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('domain', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', '%' . $search . '%');
                    });
            });
        }
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'renewal_soon') {
                $query->where('status', 'active')
                    ->whereNotNull('plan_expires_at')
                    ->whereBetween('plan_expires_at', [Carbon::now(), Carbon::now()->addDays(15)]);
            } else {
                $query->where('status', 'like', $status);
            }
        }
        $sites = $query->latest()->paginate(15);
        $plans = Plan::all();
        return view('admin.sites.index', compact('sites', 'plans'));
    }

    public function edit(Site $site)
    {
        $site->load('settings', 'plan.prices.billingPeriod', 'price.billingPeriod');
        $plans = Plan::with('prices.billingPeriod')->where('slug', '!=', 'custom')->get();
        $users = User::orderBy('name')->get();
        $billingPeriods = BillingPeriod::all();
        $customPlan = Plan::where('slug', 'custom')->first();

        return view('admin.sites.edit', compact('site', 'plans', 'users', 'billingPeriods', 'customPlan'));
    }
    public function destroy(Site $site)
    {
        // Optional: Add authorization check
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // The delete() method will remove the site from the database.
            $site->delete();

            // Redirect back to the sites index page with a success message.
            return redirect()->route('admin.sites.index')
                ->with('success', 'Site (' . $site->domain . ') has been deleted successfully.');

        } catch (\Exception $e) {
            // Optional: Catch any exceptions during deletion for robust error handling
            return back()->with('error', 'There was a problem deleting the site. Please try again.');
        }
    }

    public function update(Request $request, Site $site)
    {
        $customPlan = Plan::where('slug', 'custom')->firstOrFail();
        $isCustomPlan = $request->input('plan_id') == $customPlan->id;

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'plan_id' => ['required', 'exists:plans,id'],
            'price_id' => [Rule::requiredIf(!$isCustomPlan), 'nullable', 'exists:prices,id'],
            'plan_expires_at' => 'nullable|date',
            'status' => 'required|in:active,inactive,suspended,expired',
            'custom_price_amount' => [Rule::requiredIf($isCustomPlan), 'nullable', 'numeric', 'min:0'],
            'custom_billing_period_id' => [Rule::requiredIf($isCustomPlan), 'nullable', 'exists:billing_periods,id'],
            'custom_limits' => 'nullable|array',
            'custom_limits.*' => 'nullable|integer|min:0',
            'checkout_tracking_enabled' => 'required|boolean',
            'fraud_blocker_enabled' => 'required|boolean',
            'courier_service_enabled' => 'required|boolean',
            'data_retention_days' => 'required|integer',
        ]);

        $updateData = [
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
        ];

        $customLimits = array_filter($request->input('custom_limits', []), fn($value) => $value !== null && $value !== '');
        $updateData['custom_limits'] = count($customLimits) > 0 ? $customLimits : null;

        $newPlanId = $validated['plan_id'];

        if ($isCustomPlan) {
            $updateData['plan_id'] = $customPlan->id;
            $updateData['price_id'] = null;
            $updateData['custom_price_amount'] = $validated['custom_price_amount'];
            $updateData['custom_billing_period_id'] = $validated['custom_billing_period_id'];
        } else {
            $updateData['plan_id'] = $validated['plan_id'];
            $updateData['price_id'] = $validated['price_id'];
            $updateData['custom_price_amount'] = null;
            $updateData['custom_billing_period_id'] = null;
        }

        $isPlanChanging = $newPlanId != $site->plan_id;
        $isCustomBillingPeriodChanging = $isCustomPlan && ($validated['custom_billing_period_id'] != $site->custom_billing_period_id);

        $recalculateDate = $isPlanChanging || $isCustomBillingPeriodChanging;

        if ($request->filled('plan_expires_at')) {
            $updateData['plan_expires_at'] = $validated['plan_expires_at'];
            if ($isPlanChanging) {
                $updateData['plan_activated_at'] = Carbon::now();
            }
        } elseif ($recalculateDate) {
            $updateData['plan_activated_at'] = Carbon::now();

            $newBillingPeriod = $isCustomPlan
                ? BillingPeriod::find($validated['custom_billing_period_id'])
                : \App\Models\Price::find($validated['price_id'])->billingPeriod;

            if ($newBillingPeriod && $newBillingPeriod->duration_in_days > 0) {
                $updateData['plan_expires_at'] = Carbon::now()->addDays($newBillingPeriod->duration_in_days);
            } else {
                $updateData['plan_expires_at'] = null;
            }
        }

        $site->update($updateData);

        $site->settings()->updateOrCreate(['site_id' => $site->id], [
            'checkout_tracking_enabled' => $validated['checkout_tracking_enabled'],
            'fraud_blocker_enabled' => $validated['fraud_blocker_enabled'],
            'courier_service_enabled' => $validated['courier_service_enabled'],
            'data_retention_days' => $validated['data_retention_days'],
        ]);

        return redirect()->route('admin.sites.index')->with('success', 'Site updated successfully.');
    }
    // --- All your existing methods (index, edit, destroy, update) are above and remain unchanged. ---


    // --- START: ADD THE FOLLOWING NEW METHODS ---

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // This logic is similar to your edit() method to ensure consistency
        $users = User::where('is_admin', true)->orderBy('name')->get();
        $prices = Price::with('plan', 'billingPeriod')->get()->sortBy(function ($price) {
            return $price->plan->name . ' - ' . $price->billingPeriod->name;
        });

        return view('admin.sites.create', compact('users', 'prices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain' => 'required|string|max:255|unique:sites,domain',
            'user_id' => 'required|exists:users,id',
            'price_id' => 'required|exists:prices,id',
        ]);

        $price = Price::find($validated['price_id']);
        $billingPeriod = $price->billingPeriod;

        $nextBillingDate = null;
        if ($billingPeriod && $billingPeriod->duration_in_days > 0) {
            $nextBillingDate = now()->addDays($billingPeriod->duration_in_days);
        }

        Site::create([
            'domain' => $validated['domain'],
            'user_id' => $validated['user_id'],
            'price_id' => $validated['price_id'],
            'plan_id' => $price->plan_id,
            'plan_activated_at' => now(),
            'plan_expires_at' => $nextBillingDate,
            'status' => 'active',

            // --- FIX: ADD THIS LINE ---
            'admin_email' => auth()->user()->email,
        ]);

        return redirect()->route('admin.sites.index')->with('success', 'Site created and assigned successfully.');
    }

    // --- END: OF NEW METHODS ---

    public function paymentHistory(Request $request)
    {
        $query = Invoice::with('user', 'site');
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', fn($q) => $q->where('email', 'like', '%' . $request->search . '%'))
                ->orWhereHas('site', fn($q) => $q->where('domain', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $invoices = $query->latest()->paginate(20);
        return view('admin.payment-history.index', compact('invoices'));
    }

    public function userIndex(Request $request)
    {
        $query = User::withCount('sites');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        $users = $query->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function userCreate()
    {
        return view('admin.users.create');
    }

    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'is_admin' => ['required', 'boolean'],
            'phone_verified' => ['required', 'boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $validated['is_admin'],
            'email_verified_at' => now(),
            'phone_verified_at' => $validated['phone_verified'] ? now() : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function userEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function userUpdate(Request $request, User $user)
    {
        // MODIFIED: Removed 'phone_verified' from the validation rules.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'is_admin' => ['required', 'boolean'],
        ]);

        if ($user->id === auth()->id() && !$validated['is_admin']) {
            return back()->with('error', 'You cannot remove your own admin status.');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];
        $user->is_admin = $validated['is_admin'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', __('messages.user_updated_success'));
    }

    public function userDestroy(User $user)
    {
        if ($user->id === 1 || $user->id === auth()->id()) {
            return back()->with('error', 'This core admin user cannot be deleted.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', __('messages.user_deleted_success'));
    }

    public function manualVerifyEmail(User $user)
    {
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            return back()->with('success', 'Email address has been manually verified.');
        }
        return back()->with('info', 'Email was already verified.');
    }

    /**
     * Manually mark a user's phone as verified.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function manualVerifyPhone(User $user)
    {
        if (!$user->phone_verified_at) {
            $user->forceFill(['phone_verified_at' => now()])->save();
            return back()->with('success', 'Phone number has been manually verified.');
        }
        return back()->with('info', 'Phone was already verified.');
    }
}