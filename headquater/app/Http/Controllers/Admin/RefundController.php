<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction; // Ensure the Transaction model is imported
use Illuminate\Http\Request;
use Karim007\LaravelBkashTokenize\Facade\BkashRefundTokenize;

class RefundController extends Controller
{
    /**
     * Process a refund for a given invoice.
     */
    public function refund(Request $request, Invoice $invoice) // <-- THE FIX: Renamed 'store' to 'refund'
    {
        // 1. Authorization & Validation
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        if ($invoice->status !== 'paid') {
            return back()->with('error', 'Only paid invoices can be refunded.');
        }
        $validated = $request->validate(['reason' => 'required|string|max:255']);

        // 2. Find the original transaction details needed for the refund
        $originalTransaction = $invoice->transactions()->where('type', 'payment')->where('status', 'completed')->first();

        if (!$originalTransaction || !$originalTransaction->gateway_payment_id || !$originalTransaction->gateway_transaction_id) {
            return back()->with('error', 'Original transaction details not found. Cannot process refund.');
        }

        // 3. Call the bKash Refund API
        $response = BkashRefundTokenize::refund(
            $originalTransaction->gateway_payment_id,
            $originalTransaction->gateway_transaction_id,
            $invoice->total_amount,
            $validated['reason'],
            'sku_placeholder' // SKU is required by the API but can be a placeholder
        );

        // 4. Handle the bKash API response
        if (isset($response['statusCode']) && $response['statusCode'] == "0000" && $response['transactionStatus'] == "Completed") {

            // 5. Update local records on successful refund
            $invoice->update(['status' => 'refunded']);
            $invoice->site()->update(['status' => 'expired']);

            // 6. Create a new transaction record for the refund for auditing
            Transaction::create([
                'user_id' => $invoice->user_id,
                'site_id' => $invoice->site_id,
                'price_id' => $originalTransaction->price_id,
                'invoice_id' => $invoice->id,
                'type' => 'refunded',
                'amount' => -1 * abs($invoice->total_amount),
                'currency' => $response['currency'],
                'gateway' => 'bkash',
                'gateway_payment_id' => $originalTransaction->gateway_payment_id,
                'gateway_transaction_id' => $response['refundTrxID'],
                'status' => 'refunded',
            ]);

            return back()->with('success', 'Refund processed successfully! The invoice has been marked as refunded.');
        }

        // If the refund fails, return the error message from bKash
        return back()->with('error', 'bKash Refund Failed: ' . ($response['statusMessage'] ?? 'Unknown error.'));
    }
}