<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.5in; }
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.3; }

        /* Header Section */
        .header { text-align: center; margin-bottom: 20px; }
        .header p { margin: 0; padding: 0; }
        .republic { font-size: 10pt; }
        .dept { font-size: 14pt; font-weight: bold; margin-top: 5px; }
        .office { font-size: 11pt; margin-bottom: 10px; }
        .to-title { font-size: 16pt; font-weight: bold; text-decoration: underline; margin-top: 15px; }

        /* Main Table Body */
        .main-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .main-table td { border: 1px solid black; padding: 8px; vertical-align: top; }
        .label { font-weight: bold; background-color: #f2f2f2; width: 25%; font-size: 10pt; }

        /* Signature Blocks */
        .sig-table { width: 100%; margin-top: 25px; border-collapse: collapse; }
        .sig-table td { border: none; width: 50%; padding-top: 20px; padding-bottom: 20px; vertical-align: top; }
        .sig-label { font-size: 11pt; margin-bottom: 30px; display: block; }
        .name-line { font-weight: bold; text-transform: uppercase; text-decoration: underline; display: block; margin-top: 30px; }
        .pos-line { font-style: italic; font-size: 10pt; display: block; }

        .meta-footer { margin-top: 30px; font-size: 8pt; color: #666; border-top: 1px solid #ccc; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <p class="republic">Republic of the Philippines</p>
        <p class="dept">DEPARTMENT OF AGRARIAN REFORM</p>
        <p class="office">Davao de Oro Provincial Office</p>
        <p class="to-title">TRAVEL ORDER</p>
        <p style="margin-top: 5px;"><strong>No: {{ $order->travel_order_no }}</strong></p>
    </div>

    <table class="main-table">
        <tr>
            <td class="label">NAME</td>
            <td><strong>{{ $order->name }}</strong></td>
        </tr>
        <tr>
            <td class="label">POSITION</td>
            <td>{{ $order->position }}</td>
        </tr>
        <tr>
            <td class="label">OFFICIAL STATION</td>
            <td>{{ $order->station }}</td>
        </tr>
        <tr>
            <td class="label">DESTINATION</td>
            <td><strong>{{ $order->destination }}</strong> ({{ ucwords(str_replace('_', ' ', $order->travel_type)) }})</td>
        </tr>
        <tr>
            <td class="label">DATE(S) OF TRAVEL</td>
            <td>
                {{ \Carbon\Carbon::parse($order->departure_date)->format('F d, Y') }}
                to
                {{ \Carbon\Carbon::parse($order->return_date)->format('F d, Y') }}
            </td>
        </tr>
        <tr>
            <td class="label">PURPOSE OF TRAVEL</td>
            <td>
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($order->purpose_of_trip as $purpose)
                        <li>{{ $purpose }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        <tr>
            <td class="label">TRANSPORTATION</td>
            <td>{{ $order->transportation_means }} - {{ $order->vehicle_type }}</td>
        </tr>
    </table>

    {{-- Signatures Section --}}
    <table class="sig-table">
        <tr>
            {{-- PREPARED BY (The Creator/Owner) --}}
            <td>
                <span class="sig-label">Prepared by:</span>
                <span class="name-line">{{ $order->user->fullname ?? $order->name }}</span>
                <span class="pos-line">{{ $order->user->position ?? $order->position }}</span>
            </td>

            {{-- RECOMMENDING APPROVAL --}}
            <td>
                @if($order->recommending_approval && $order->recommending_approval !== 'N/A')
                    <span class="sig-label">Recommending Approval:</span>
                    <span class="name-line">{{ $order->recommending_approval }}</span>
                    <span class="pos-line">{{ $order->recommending_position }}</span>
                @else
                    &nbsp;
                @endif
            </td>
        </tr>
        <tr>
            {{-- APPROVED BY --}}
            <td>
                <span class="sig-label">Approved by:</span>
                <span class="name-line">{{ $order->approved_by_name }}</span>
                <span class="pos-line">{{ $order->approved_by_position }}</span>
            </td>

            {{-- FUNDS AVAILABLE --}}
            <td>
                <span class="sig-label">Funds Available:</span>
                <span class="name-line">{{ $order->fund_custodian }}</span>
                <span class="pos-line">Budget Officer / Cashier</span>
            </td>
        </tr>
    </table>

    <div class="meta-footer">
        Reference ID: {{ $order->id }} | Date Printed: {{ now()->format('m/d/Y h:i A') }} | DAR Davao de Oro Travel System
    </div>

</body>
</html>
