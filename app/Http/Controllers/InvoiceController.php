<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function pdf(Invoice $invoice)
    {
        $invoice->load('intervention.car.user', 'intervention.service');
        $user = auth()->user();

        // Client can download their own invoices
        // Service can download invoices for their interventions
        $isOwner = $invoice->intervention->car->user_id === $user->id;
        $isService = $user->isService() && $user->service && $invoice->intervention->service_id === $user->service->id;
        $isAdmin = $user->isAdmin();

        abort_unless($isOwner || $isService || $isAdmin, 403);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download("factura-{$invoice->number}.pdf");
    }
}
