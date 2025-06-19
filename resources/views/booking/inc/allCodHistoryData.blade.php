@php
    $sr = 1;
    $totalAmount = 0.0;
@endphp
@foreach ($data as $codHistory)
    <tr>
        <td>{{ $sr++ }}</td>
        <td>{{ $codHistory->datetime }}</td>
        <td>{{ $codHistory->order_id }}</td>
        <td>{{ $codHistory->pyment_method }}</td>
        <td>{{ '₹ ' . $codHistory->price }}</td>
    </tr>
    @php
        $totalAmount += $codHistory->order->price;
    @endphp
@endforeach
<input type="hidden" id="totalAmount" value="{{ $totalAmount }}">