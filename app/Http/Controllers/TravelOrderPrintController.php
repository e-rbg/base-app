<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Auth;

class TravelOrderPrintController extends Controller
{
    public function print($id)
    {
        $order = TravelOrder::findOrFail($id);
        $this->authorize($order);

        return Pdf::view('pdf.travel-order', [
                'order' => $order,
                'municipality' => $this->resolveMunicipality($order->station),
            ])
            ->format('a4')
            ->name("TO-{$order->travel_order_no}.pdf");
    }

    public function downloadPdf($id)
    {
        $order = TravelOrder::findOrFail($id);
        $this->authorize($order);

        return Pdf::view('pdf.travel-order', [
                'order' => $order,
                'municipality' => $this->resolveMunicipality($order->station),
            ])
            ->format('a4')
            ->name("Travel-Order-{$order->travel_order_no}.pdf");
    }

    private function authorize(TravelOrder $order): void
    {
        $user = Auth::user();

        $isAuthorized =
            ($order->user_id === $user->id) ||
            $user->isSuperAdmin() ||
            str_contains($order->approved_by_name, $user->fullname) ||
            str_contains($order->recommending_approval ?? '', $user->fullname);

        if (!$isAuthorized) {
            abort(403, 'Unauthorized access to this Travel Order.');
        }
    }

    private function resolveMunicipality($station): string
    {
        if (str_contains(strtoupper($station), 'DARMO')) {
            $parts = explode('-', $station);
            return trim($parts[1] ?? $station);
        }
        return 'Nabunturan';
    }
}
