<?php

namespace App\Http\Controllers;

use App\Http\Traits\ManagesSubscriptions;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;

class BkashTokenizePaymentController extends Controller
{
    use ManagesSubscriptions;

    /**
     * Create a bKash payment request based on a specific invoice.
     */
    public function createPayment(Request $request, Invoice $invoice)
    {
        // Authorize: Ensure the user owns this invoice
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate: Ensure the invoice is still due
        if ($invoice->status !== 'due') {
            return redirect()->route('invoices.show', $invoice)->with('error', 'This invoice is already paid or has been cancelled.');
        }

        // Store the invoice ID in the session to retrieve after the bKash redirect
        session(['bkash_payment_invoice_id' => $invoice->id]);

        // Prepare the data for the bKash API
        $data = [
            'intent' => 'sale',
            'mode' => '0011', // For Tokenized Checkout
            'payerReference' => $invoice->id,
            'currency' => 'BDT',
            'amount' => round($invoice->total_amount, 2),
            'merchantInvoiceNumber' => $invoice->invoice_number,
            'callbackURL' => route('bkash.success')
        ];

        $response = BkashPaymentTokenize::cPayment(json_encode($data));

        // Redirect to bKash portal or show an error
        if (isset($response['bkashURL'])) {
            return redirect()->away($response['bkashURL']);
        } else {
            return redirect()->back()->with('error', $response['statusMessage'] ?? 'bKash Error: Could not initiate payment.');
        }
    }

    /**
     * Handle the callback from bKash after a payment attempt.
     */
    public function success(Request $request)
    {
        $invoice_id = session('bkash_payment_invoice_id');
        if (!$invoice_id) {
            return redirect()->route('dashboard')->with('error', 'Payment session expired or invalid. Please try again.');
        }
        $invoice = \App\Models\Invoice::find($invoice_id);

        if ($request->status !== 'success') {
            $invoice->update(['status' => 'cancelled']);
            $errorMessage = "Payment was " . ucfirst($request->status) . ".";
            return redirect()->route('invoices.show', $invoice)->with('error', $errorMessage);
        }

        $response = BkashPaymentTokenize::executePayment($request->paymentID);
        if (!$response) {
            $response = BkashPaymentTokenize::queryPayment($request->paymentID);
        }

        if (isset($response['statusCode']) && $response['statusCode'] == "0000" && $response['transactionStatus'] == "Completed") {

            // THE FIX: Pass both trxID and paymentID to the activation logic
            $this->activatePlanForInvoice($invoice, $response['trxID'], $response['paymentID']);

            session()->forget('bkash_payment_invoice_id');
            $price = $invoice->items->first()->price;
            $successMessage = "Payment successful! The '{$price->plan->name}' plan has been activated for {$invoice->site->domain}.";
            return redirect()->route('invoices.show', $invoice)->with('success', $successMessage);
        }

        return redirect()->route('invoices.show', $invoice)->with('error', $response['statusMessage'] ?? 'Could not verify payment.');
    }
}