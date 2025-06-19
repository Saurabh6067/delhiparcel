@php
    $sr = 1;
    $totalAmount = 0.0;
@endphp
@foreach ($data as $codHistory)
    <tr>
        <td>{{ $sr++ }}</td>
        <td>{{ $codHistory->datetime ?? '' }}</td>
        <td>{{ $codHistory->order->order_id ?? '' }}</td>
        <td>{{ $codHistory->order->order_status ?? '' }}</td>
        <td>{{ $codHistory->deliveryBoy->name ?? '' }}</td>
        <td>{{ $codHistory->pyment_method ?? '' }}</td>
        <td>{{ '₹ ' . $codHistory->order->price ?? '' }}</td>
    </tr>
    @php
        $totalAmount += $codHistory->order->price;
    @endphp
@endforeach
<input type="hidden" id="totalAmount" value="{{ $totalAmount }}">