<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingPeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BillingPeriodController extends Controller
{
    public function index()
    {
        $billingPeriods = BillingPeriod::all();
        return view('admin.billing-periods.index', compact('billingPeriods'));
    }

    public function create()
    {
        return view('admin.billing-periods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());
        BillingPeriod::create($validated);
        return redirect()->route('admin.billing-periods.index')->with('success', 'Billing period created successfully.');
    }

    public function edit(BillingPeriod $billingPeriod)
    {
        return view('admin.billing-periods.edit', compact('billingPeriod'));
    }

    public function update(Request $request, BillingPeriod $billingPeriod)
    {
        $validated = $request->validate($this->validationRules($billingPeriod->id));
        $billingPeriod->update($validated);
        return redirect()->route('admin.billing-periods.index')->with('success', 'Billing period updated successfully.');
    }

    public function destroy(BillingPeriod $billingPeriod)
    {
        // Optional: Add a check to prevent deletion if the period is in use.
        // if ($billingPeriod->prices()->exists()) {
        //     return back()->with('error', 'Cannot delete a billing period that is in use by a plan.');
        // }
        $billingPeriod->delete();
        return redirect()->route('admin.billing-periods.index')->with('success', 'Billing period deleted successfully.');
    }

    protected function validationRules($ignoreId = null)
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('billing_periods')->ignore($ignoreId)],
            'duration_in_days' => 'required|integer|min:0',
        ];
    }
}