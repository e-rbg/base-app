<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelOrderController extends Controller
{
    /**
     * Display a listing of travel orders.
     */
    public function index(): View
    {
        $orders = TravelOrder::latest()->paginate(10);
        return view('travel-orders.index', compact('orders'));
    }

    /**
     * Display the print-friendly version of the travel order.
     */
    public function print(TravelOrder $travelOrder): View
    {
        // Ensure purpose_of_trip is an array even if it's stored as a string
        if (is_string($travelOrder->purpose_of_trip)) {
            $travelOrder->purpose_of_trip = json_decode($travelOrder->purpose_of_trip, true) ?? [$travelOrder->purpose_of_trip];
        }

        // We load the user relationship if you want to show who prepared it
        $travelOrder->load('user');

        return view('travel-orders.print', [
            'order' => $travelOrder,
            'title' => "Travel Order - " . $travelOrder->travel_order_no,
            'municipality' => $travelOrder->municipality ?? 'Nabunturan' // Default fallback
        ]);
    }

    /**
     * Show the form for creating a new travel order.
     * (Assuming you're using Livewire for the actual form)
     */
    public function create(): View
    {
        return view('travel-orders.create');
    }
}