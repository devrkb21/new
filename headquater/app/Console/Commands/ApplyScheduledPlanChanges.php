<?php

namespace App\Console\Commands;

use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ApplyScheduledPlanChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:apply-changes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find any subscriptions with a scheduled plan change that is due and apply the new plan.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for scheduled plan changes...');

        // Find sites that have a scheduled change date in the past or today.
        $sitesToChange = Site::with('nextPrice.billingPeriod', 'user') // Eager load user for notifications
            ->whereNotNull('next_plan_id')
            ->whereNotNull('plan_change_at')
            ->where('plan_change_at', '<=', Carbon::now())
            ->get();

        if ($sitesToChange->isEmpty()) {
            $this->info('No plan changes are due to be applied.');
            return 0;
        }

        $count = $sitesToChange->count();
        $this->info("Found {$count} site(s) with a scheduled plan change. Applying now...");

        foreach ($sitesToChange as $site) {
            $nextPrice = $site->nextPrice;
            $oldPlanName = $site->plan->name; // For notification

            if (!$nextPrice) {
                Log::warning("Skipping site #{$site->id} ({$site->domain}) due to missing next_price record.");
                continue;
            }

            // --- CORRECTED LOGIC ---
            // Calculate the new expiration date based on the new plan's duration, starting from the scheduled change date.
            $baseDate = $site->plan_change_at;
            $new_expires_at = null;
            if ($nextPrice->billingPeriod->duration_in_days > 0) {
                $new_expires_at = $baseDate->addDays($nextPrice->billingPeriod->duration_in_days);
            }

            // Apply the new plan
            $site->plan_id = $site->next_plan_id;
            $site->price_id = $site->next_price_id;
            $site->plan_expires_at = $new_expires_at;
            $site->plan_activated_at = Carbon::now(); // Mark when this new plan term started

            // Clear the scheduled change fields
            $site->next_plan_id = null;
            $site->next_price_id = null;
            $site->plan_change_at = null;

            $site->save();

            // Notify the user of the successful change
            $site->user->notifyUser('Plan Changed Successfully', "Your scheduled plan change for {$site->domain} from '{$oldPlanName}' to '{$nextPrice->plan->name}' has been successfully applied.");

            Log::info("Applied scheduled plan change for site #{$site->id} ({$site->domain}). New expiry: {$new_expires_at}");
        }

        $this->info("Successfully processed {$count} scheduled plan change(s).");
        return 0;
    }
}