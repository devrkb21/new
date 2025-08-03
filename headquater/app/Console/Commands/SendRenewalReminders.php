<?php

namespace App\Console\Commands;

use App\Models\Site;
use App\Models\Invoice;
use App\Mail\RenewalReminderMail;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendRenewalReminders extends Command
{
    protected $signature = 'subscriptions:send-reminders';
    protected $description = 'Generate invoices and send renewal reminders for subscriptions expiring within 7 days.';

    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    public function handle()
    {
        $this->info('Checking for subscriptions to remind...');

        $now = Carbon::now();
        $reminderPeriodEnd = $now->copy()->addDays(7);

        // Find sites that have a paid plan expiring within the next 7 days.
        $expiringSites = Site::with(['user', 'plan', 'price'])
            ->where('status', 'active')
            ->whereNotNull('plan_expires_at')
            // This is the new, flexible date range check
            ->whereBetween('plan_expires_at', [$now, $reminderPeriodEnd])
            // This is the new, correct way to check for a paid plan
            ->whereHas('price', function ($query) {
                $query->where('amount', '>', 0);
            })
            ->get();

        if ($expiringSites->isEmpty()) {
            $this->info("No paid subscriptions found expiring in the next 7 days.");
            return 0;
        }

        $this->info("Found {$expiringSites->count()} subscription(s) to process...");

        foreach ($expiringSites as $site) {
            // The due date is always 7 days after the plan expires.
            $dueDate = Carbon::parse($site->plan_expires_at)->addDays(7);

            // CRITICAL CHECK: See if an due invoice for this exact due date already exists.
            // This prevents sending duplicate reminders.
            $invoiceExists = Invoice::where('site_id', $site->id)
                ->where('status', 'due')
                ->whereDate('due_date', '=', $dueDate)
                ->exists();

            if ($invoiceExists) {
                $this->warn("Skipping site #{$site->id} ({$site->domain}). A renewal invoice already exists for this cycle.");
                continue;
            }

            // If we get here, no reminder has been sent. Proceed.
            DB::beginTransaction();
            try {
                // Generate a unique invoice number
                $invoiceNumber = 'INV-' . strtoupper(Str::uuid());

                $invoice = Invoice::create([
                    'user_id' => $site->user->id,
                    'site_id' => $site->id,
                    'invoice_number' => $invoiceNumber,
                    'status' => 'due',
                    'total_amount' => $site->price->amount,
                    'due_date' => $dueDate,
                ]);

                $this->info("Generated Invoice #{$invoice->id} ({$invoice->invoice_number}) for site #{$site->id}.");

                $invoice->items()->create([
                    'description' => "Subscription Renewal: {$site->plan->name} plan for {$site->domain}",
                    'price_id' => $site->price_id,
                    'quantity' => 1,
                    'price' => $site->price->amount,
                    'amount' => $site->price->amount,
                ]);

                // The corrected line with all three arguments
                Mail::to($site->user)->send(new RenewalReminderMail($site->user, $site, $invoice));

                if ($site->user->phone) {
                    $message = "[Coder Zone BD] Your subscription for {$site->domain} expires soon. Invoice {$invoiceNumber} is due by " . $dueDate->format('d-M-Y') . ". Please log in to renew.";
                    $this->smsService->send($site->user->phone, $message);
                }

                $site->user->notifications()->create([
                    'title' => 'Subscription Renewal',
                    'body' => "The invoice for your {$site->plan->name} plan renewal has been generated. Please pay by " . $dueDate->format('M d, Y') . ".",
                    'link' => route('invoices.show', $invoice->id)
                ]);

                DB::commit();
                Log::info("Successfully processed renewal and sent reminders for site #{$site->id}.");

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to process renewal for site #{$site->id}: " . $e->getMessage());
                $this->error("Failed for site #{$site->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully processed renewal reminders.");
        return 0;
    }
}