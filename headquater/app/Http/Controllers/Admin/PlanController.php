<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingPeriod;
use App\Models\Plan;
use App\Models\Site;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('prices.billingPeriod')->withCount('sites')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        $plan = new Plan();
        $billingPeriods = BillingPeriod::all();
        return view('admin.plans.create', compact('plan', 'billingPeriods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());
        $plan = Plan::create($validated);

        if ($request->has('prices')) {
            foreach ($request->prices as $priceData) {
                $plan->prices()->create($priceData);
            }
        }
        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        $plan->load('prices');
        $billingPeriods = BillingPeriod::all();
        return view('admin.plans.edit', compact('plan', 'billingPeriods'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate($this->validationRules($plan->id));
        $plan->update($validated);

        $plan->prices()->delete();
        if ($request->has('prices')) {
            foreach ($request->prices as $priceData) {
                $plan->prices()->create($priceData);
            }
        }
        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->slug === 'free') {
            return back()->with('error', 'The Free Tier plan cannot be deleted.');
        }

        $sitesUsingPlan = Site::where('plan_id', $plan->id)
            ->orWhere('next_plan_id', $plan->id)
            ->count();

        if ($sitesUsingPlan > 0) {
            return back()->with('error', 'Cannot delete a plan that is active on a site or is scheduled for a future change.');
        }

        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }

    protected function validationRules($ignoreId = null)
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $ignoreId,
            'is_public' => 'required|boolean', // Added validation for our new field
            'limit_checkouts' => 'required|integer|min:0',
            'limit_fraud_ips' => 'required|integer|min:0',
            'limit_fraud_emails' => 'required|integer|min:0',
            'limit_fraud_phones' => 'required|integer|min:0',
            'limit_courier_checks' => 'required|integer|min:0',
            'prices' => 'nullable|array',
            'prices.*.amount' => 'required|numeric|min:0',
            'prices.*.billing_period_id' => 'required|exists:billing_periods,id',
        ];
    }
}