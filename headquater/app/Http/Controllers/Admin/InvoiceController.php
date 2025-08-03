<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ManagesSubscriptions; // <-- ADD THIS LINE
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    use ManagesSubscriptions; // <-- AND THIS LINE

    public function show(Invoice $invoice)
    {
        // Eager load the relationships to prevent extra database queries
        $invoice->load('user', 'site', 'items.price.plan', 'transactions');

        return view('admin.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        $invoice->load('items.price.plan', 'items.price.billingPeriod', 'site', 'user');
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }

    // ADD THIS NEW METHOD
    /**
     * Manually mark a due invoice as paid and activate the plan.
     */
    public function markAsPaid(Invoice $invoice)
    {
        if ($invoice->status !== 'due') {
            return back()->with('error', 'Only due invoices can be marked as paid.');
        }

        // Use the reusable trait to do all the work
        $this->activatePlanForInvoice($invoice, 'manual-admin-' . auth()->id());

        return redirect()->route('admin.invoices.show', $invoice)->with('success', 'Invoice has been manually marked as paid and the plan is activated.');
    }
}