<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TravelOrderPrintController extends Controller
{
    public function print(TravelOrder $order)
    {
        $user = Auth::user();

        // 1. Comprehensive Security Check
        $isAuthorized =
            ($order->user_id === $user->id) ||                 // The Creator
            ($user->isSuperAdmin()) ||                         // The Admin
            str_contains($order->approved_by_name, $user->fullname) ||   // The Final Approver
            str_contains($order->recommending_approval ?? '', $user->fullname); // The Recommender

        if (!$isAuthorized) {
            abort(403, 'Unauthorized access to this Travel Order.');
        }

        // 2. Generate PDF
        // Note: We use the stored position fields so the PDF is always accurate
        $pdf = Pdf::loadView('pdf.travel-order', [
            'order' => $order
        ]);

        return $pdf->setPaper('a4', 'portrait')
                   ->stream("TO-{$order->travel_order_no}.pdf");
    }
}
