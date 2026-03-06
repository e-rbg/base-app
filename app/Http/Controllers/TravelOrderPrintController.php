<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TravelOrderPrintController extends Controller
{
    public function print($id) // Changed to $id for consistency
    {
        $order = TravelOrder::findOrFail($id);
        $user = Auth::user();

        // 1. Security Check
        $isAuthorized = 
            ($order->user_id === $user->id) || 
            ($user->isSuperAdmin()) || 
            str_contains($order->approved_by_name, $user->fullname) || 
            str_contains($order->recommending_approval ?? '', $user->fullname);

        if (!$isAuthorized) {
            abort(403, 'Unauthorized access to this Travel Order.');
        }

        $municipality = $this->resolveMunicipality($order->station);

        $pdf = Pdf::loadView('pdf.travel-order', [
            'order' => $order,
            'municipality' => $municipality 
        ]);

        return $pdf->setPaper('a4', 'portrait')
                   ->stream("TO-{$order->travel_order_no}.pdf");
    }

    public function downloadPdf($id)
    {
        $order = TravelOrder::findOrFail($id);
        $municipality = $this->resolveMunicipality($order->station);

        $pdf = Pdf::loadView('pdf.travel-order', [
            'order' => $order,
            'municipality' => $municipality 
        ]);

        return $pdf->setPaper('a4', 'portrait') // Added paper size here too
                   ->download("Travel-Order-{$order->travel_order_no}.pdf");
    }

    private function resolveMunicipality($station) {
        if (str_contains(strtoupper($station), 'DARMO')) {
            $parts = explode('-', $station);
            return trim($parts[1] ?? $station);
        }
        return 'Nabunturan';
    }
}