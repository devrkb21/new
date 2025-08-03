<?php

namespace App\Http\Controllers;

use App\Http\Traits\ManagesSubscriptions;
use App\Models\Invoice;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class InvoiceController extends Controller
{
    use ManagesSubscriptions;

    // The store() method remains the same and does not need to be changed.
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'price_id' => 'required|exists:prices,id',
            'site_id' => 'required|exists:sites,id',
        ]);
        $price = \App\Models\Price::with('plan', 'billingPeriod')->find($validated['price_id']);
        $site = auth()->user()->sites()->findOrFail($validated['site_id']);
        $invoice = Invoice::create([
            'user_id' => auth()->id(),
            'site_id' => $site->id,
            'invoice_number' => 'INV-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'status' => 'due',
            'total_amount' => $price->amount,
            'due_date' => \Carbon\Carbon::now()->addDays(7),
        ]);
        $invoice->items()->create([
            'price_id' => $price->id,
            'description' => $price->plan->name . ' - ' . $price->billingPeriod->name,
            'amount' => $price->amount,
        ]);
        if ($invoice->total_amount <= 0) {
            $this->activatePlanForInvoice($invoice);
            return redirect()->route('dashboard')->with('success', "The '{$price->plan->name}' plan has been successfully activated for {$site->domain}.");
        }
        // --- ADD THIS BLOCK ---
        // For paid plans, create a notification before redirecting
        $invoice->user->notifications()->create([
            'title' => 'New Invoice #' . $invoice->invoice_number,
            'body' => 'An invoice for ৳' . number_format($invoice->total_amount, 2) . ' has been generated for your review and payment.',
            'link' => route('invoices.show', $invoice)
        ]);
        // --- END OF NEW BLOCK ---
        return redirect()->route('invoices.show', $invoice);
    }

    public function show(Invoice $invoice)
    {
        // THE FIX: Replaced authorize() with a manual check for security
        if ($invoice->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        // We added 'transactions' here previously to show the history
        $invoice->load('items.price.plan', 'items.price.billingPeriod', 'site', 'transactions');

        return view('invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        // THE FIX: Allow access if the user is the owner OR is an admin
        if ($invoice->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }
        $invoice->load('items.price.plan', 'items.price.billingPeriod', 'site', 'user', 'transactions');
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function cancel(Invoice $invoice)
    {
        if ($invoice->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }
        if ($invoice->status !== 'due') {
            return back()->with('error', 'This invoice cannot be cancelled.');
        }
        $invoice->update(['status' => 'cancelled']);
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.payment.history')->with('success', 'Invoice has been cancelled.');
        }
        return redirect()->route('invoices.show', $invoice)->with('success', 'Your invoice has been cancelled.');
    }
}