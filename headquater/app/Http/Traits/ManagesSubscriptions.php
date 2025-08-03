<?php

namespace App\Http\Traits;

use App\Models\Invoice;
use App\Models\Price;
use Carbon\Carbon;

trait ManagesSubscriptions
{
    /**
     * Determines if a plan change is an upgrade based on normalized daily cost.
     *
     * @param Price $currentPrice
     * @param Price $newPrice
     * @return bool
     */
    protected function isUpgrade(Price $currentPrice, Price $newPrice): bool
    {
        // =================================================================
        //  THE CORRECTED LOGIC
        //  This hard-coded rule always treats a move to the 'free' plan as a downgrade.
        // =================================================================
        if ($newPrice->plan->slug === 'free') {
            return false;
        }

        // SCENARIO 1: Moving TO a free plan (redundant but safe) is ALWAYS a downgrade.
        if ($newPrice->amount <= 0) {
            return false;
        }

        // SCENARIO 2: Moving FROM a free plan TO a paid plan is ALWAYS an upgrade.
        if ($currentPrice->amount <= 0) {
            return true;
        }

        // SCENARIO 3: Moving FROM a lifetime (paid) plan TO a recurring plan is a downgrade.
        if ($currentPrice->billingPeriod->duration_in_days <= 0 && $newPrice->billingPeriod->duration_in_days > 0) {
            return false;
        }

        // SCENARIO 3.5: Moving TO a lifetime (paid) plan FROM a recurring plan is an upgrade.
        if ($newPrice->billingPeriod->duration_in_days <= 0 && $currentPrice->billingPeriod->duration_in_days > 0) {
            return true;
        }


        // SCENARIO 4: Both are paid, recurring plans. Compare their daily rates.
        if ($currentPrice->billingPeriod->duration_in_days > 0 && $newPrice->billingPeriod->duration_in_days > 0) {
            $currentDailyRate = $currentPrice->amount / $currentPrice->billingPeriod->duration_in_days;
            $newDailyRate = $newPrice->amount / $newPrice->billingPeriod->duration_in_days;
            return $newDailyRate > $currentDailyRate;
        }

        // Fallback: If logic reaches here (e.g., lifetime to lifetime), compare amounts directly.
        return $newPrice->amount > $currentPrice->amount;
    }


    protected function activatePlanForInvoice(Invoice $invoice, ?string $trxID = null, ?string $paymentID = null): void
    {
        $site = $invoice->site;
        $invoiceItem = $invoice->items()->with('price.billingPeriod', 'price.plan')->first();
        $newPrice = $invoiceItem->price;

        if (!$newPrice || !$site) {
            return;
        }

        $invoice->update(['status' => 'paid', 'paid_at' => Carbon::now()]);

        \App\Models\Transaction::create([
            'user_id' => $invoice->user_id,
            'site_id' => $site->id,
            'price_id' => $newPrice->id,
            'invoice_id' => $invoice->id,
            'amount' => $invoice->total_amount,
            'currency' => 'BDT',
            'gateway' => $invoice->total_amount > 0 ? 'bkash' : 'none',
            'gateway_payment_id' => $paymentID,
            'gateway_transaction_id' => $trxID ?? 'free-' . $invoice->id,
            'status' => 'completed',
        ]);

        $isCurrentlyActive = ($site->status === 'active' && $site->plan_expires_at && $site->plan_expires_at->isFuture());

        if ($isCurrentlyActive && $site->price) {
            if (!$this->isUpgrade($site->price, $newPrice)) {
                $site->update([
                    'next_plan_id' => $newPrice->plan_id,
                    'next_price_id' => $newPrice->id,
                    'plan_change_at' => $site->plan_expires_at,
                ]);

                $planName = $newPrice->plan->name;
                $siteDomain = $site->domain;
                $changeDate = $site->plan_expires_at->format('M d, Y');
                $invoice->user->notifications()->create([
                    'title' => 'Plan Change Scheduled',
                    'body' => "Your plan for {$siteDomain} will be changed to '{$planName}' on {$changeDate}.",
                    'link' => route('orders.plan')
                ]);

            } else {
                $currentPrice = $site->price;
                $remainingDays = Carbon::now()->diffInDays($site->plan_expires_at, false);
                $totalDaysInCurrentCycle = $currentPrice->billingPeriod->duration_in_days;
                $credit = 0;

                if ($totalDaysInCurrentCycle > 0 && $remainingDays > 0) {
                    $dailyRateOfCurrentPlan = $currentPrice->amount / $totalDaysInCurrentCycle;
                    $credit = $remainingDays * $dailyRateOfCurrentPlan;
                }

                $newPlanDailyRate = 0;
                if ($newPrice->billingPeriod->duration_in_days > 0) {
                    $newPlanDailyRate = $newPrice->amount / $newPrice->billingPeriod->duration_in_days;
                }

                $extraDaysFromCredit = 0;
                if ($newPlanDailyRate > 0) {
                    $extraDaysFromCredit = floor($credit / $newPlanDailyRate);
                }

                $newExpiryDate = null;
                if ($newPrice->billingPeriod->duration_in_days > 0) {
                    $newExpiryDate = Carbon::now()
                        ->addDays($newPrice->billingPeriod->duration_in_days)
                        ->addDays($extraDaysFromCredit);
                }

                $site->update([
                    'plan_id' => $newPrice->plan_id,
                    'price_id' => $newPrice->id,
                    'status' => 'active',
                    'plan_expires_at' => $newExpiryDate,
                    'plan_activated_at' => Carbon::now(),
                    'next_plan_id' => null,
                    'next_price_id' => null,
                    'plan_change_at' => null,
                ]);

                $planName = $newPrice->plan->name;
                $siteDomain = $site->domain;
                $invoice->user->notifications()->create([
                    'title' => 'Plan Upgraded!',
                    'body' => "The '{$planName}' plan is now active for your site: {$siteDomain}.",
                    'link' => route('orders.plan')
                ]);
            }

        } else {
            $new_expires_at = null;
            if ($newPrice->billingPeriod->duration_in_days > 0) {
                $new_expires_at = Carbon::now()->addDays($newPrice->billingPeriod->duration_in_days);
            }

            $site->update([
                'plan_id' => $newPrice->plan_id,
                'price_id' => $newPrice->id,
                'status' => 'active',
                'plan_expires_at' => $new_expires_at,
                'plan_activated_at' => Carbon::now(),
                'next_plan_id' => null,
                'next_price_id' => null,
                'plan_change_at' => null,
            ]);

            $planName = $newPrice->plan->name;
            $siteDomain = $site->domain;
            $invoice->user->notifications()->create([
                'title' => 'Plan Activated!',
                'body' => "The '{$planName}' plan is now active for your site: {$siteDomain}.",
                'link' => route('orders.plan')
            ]);
        }
    }
}