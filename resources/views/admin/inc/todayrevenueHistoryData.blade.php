

@php
    $sr = 1;
@endphp
@foreach ($todayrevenue as $revenueHistory)
    <tr>
        <td>{{ $sr++ }}</td>
        <td>₹ {{ number_format($revenueHistory->price, 2) }}</td>
    </tr>
@endforeach
<input type="hidden" id="orderCountInput" value="{{ $orderCount ?? 0 }}">
<input type="hidden" id="totalAmount" value="{{ $toDayRevenue ?? 0 }}">
<input type="hidden" id="averageAmount" value="{{ $averageRevenue ?? 0 }}">