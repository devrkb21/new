<?php

namespace App\Console\Commands;

use App\Models\Site;
use App\Models\Plan;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckSubscriptionExpirations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expirations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downgrade subscriptions that were not renewed after the 7-day grace period.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired subscriptions to downgrade...');

        // Find the default "Free Trial" plan to downgrade users to.
        // I am assuming your free plan has `is_public` set to true.
        // Change this query if you identify the free plan differently (e.g., by name).
        $freePlan = Plan::where('is_public', true)->with('prices')->first();

        if (!$freePlan) {
            $this->error('Error: No "Free Trial" plan found. Please ensure a default free plan exists.');
            Log::critical('Downgrade check failed: No default free plan found in the database.');
            return 1;
        }

        // Find sites where the grace period ended today (i.e., expired exactly 7 days ago).
        $gracePeriodEnded = Carbon::now()->subDays(7)->startOfDay();
        $sitesToDowngrade = Site::with('user')
            ->where('status', 'active')
            ->whereNotNull('plan_expires_at')
            ->whereDate('plan_expires_at', '=', $gracePeriodEnded)
            ->get();

        if ($sitesToDowngrade->isEmpty()) {
            $this->info('No subscriptions found with an expired grace period.');
            return 0;
        }

        $count = $sitesToDowngrade->count();
        $this->info("Found {$count} site(s) to process for downgrade...");

        foreach ($sitesToDowngrade as $site) {
            DB::beginTransaction();
            try {
                // Find the latest unpaid renewal invoice for this site.
                $invoice = Invoice::where('site_id', $site->id)
                    ->where('status', 'unpaid')
                    ->latest('created_at')
                    ->first();

                // If there's no unpaid invoice, it means it was paid or never created.
                // In either case, we don't downgrade.
                if (!$invoice) {
                    $this->warn("Skipping site #{$site->id} ({$site->domain}): No unpaid renewal invoice found. The plan may have been renewed or cancelled manually.");
                    DB::rollBack();
                    continue;
                }

                // Downgrade the Site
                $site->plan_id = $freePlan->id;
                // Assign the first price associated with the free plan, or null
                $site->price_id = $freePlan->prices->first()->id ?? null;
                $site->status = 'active'; // It's now active on the free plan
                $site->plan_activated_at = Carbon::now();
                $site->plan_expires_at = null; // Assuming the free plan does not expire
                $site->save();

                // Cancel the unpaid invoice
                $invoice->update(['status' => 'cancelled']);

                // Notify user of the downgrade
                $site->user->notifications()->create([
                    'title' => 'Subscription Downgraded',
                    'body' => "We did not receive your renewal payment for site {$site->domain}. Your plan has been downgraded to the Free Trial.",
                    'link' => route('sites.manage', $site->id)
                ]);

                DB::commit();
                Log::info("Subscription for site #{$site->id} ({$site->domain}) has been downgraded to the Free Trial plan. Invoice #{$invoice->id} cancelled.");
                $this->info("Downgraded site #{$site->id}.");

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to downgrade site #{$site->id}: " . $e->getMessage());
                $this->error("Failed to downgrade site #{$site->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully processed {$count} site(s).");
        return 0;
    }
}