<x-layouts::app.base title="PDF Printing">
    <div class="bg-white p-10">
        <div class="max-w-4xl mx-auto border p-8">
            <h1 class="text-center font-bold text-2xl text-red-500">TRAVEL ORDER</h1>
            <p class="text-center">No: {{ $order->travel_order_no }}</p>
            <hr class="my-4">
            <p><strong>Personnel:</strong> {{ $order->name }}</p>
        </div>
        <h1>Boooo</h1>
    </div>
</x-layouts::app.base>
