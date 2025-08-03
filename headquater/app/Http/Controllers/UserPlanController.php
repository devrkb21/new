<?php
namespace App\Http\Controllers;
use App\Models\Plan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserPlanController extends Controller
{
    /**
     * Display a list of the user's current subscriptions/sites.
     */
    public function index(Request $request) // <-- ADD Request $request
    {
        $query = auth()->user()->sites()->with([
            'plan',
            'price.billingPeriod',
            'nextPlan',
            'nextPrice.billingPeriod'
        ]);

        // Apply status filter
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

        // Using get() instead of paginate for simplicity, as per original code.
        $sites = $query->get();

        return view('orders-plan', ['sites' => $sites]);
    }

    /**
     * Show the page for choosing a new plan to subscribe to.
     */
    // In app/Http/Controllers/UserPlanController.php
    public function create()
    {
        // Fetches only plans that have at least one price defined.
        // Eager-loads the prices and their billing periods for efficiency.
        $plans = Plan::where('is_public', true)->with('prices.billingPeriod')->get();
        return view('subscribe', ['plans' => $plans]);
    }
    public function paymentHistory()
    {
        // Get all invoices for the logged-in user, loading the related data for efficiency.
        // We'll show the most recent invoices first.
        $invoices = auth()->user()->invoices()->with('items.price.plan', 'items.price.billingPeriod')->latest()->paginate(15);

        return view('payment-history', ['invoices' => $invoices]);
    }

}