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
            ])
            ->format('a4')
            ->name("Travel-Order-{$order->travel_order_no}.pdf");
    }

    private function authorize(TravelOrder $order): void
    {
        // All authenticated users can view and print any travel order
    }
}