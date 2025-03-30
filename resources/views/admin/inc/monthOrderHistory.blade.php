<div class="row">
    @php
        $total = $monthOrders->count() * ($monthOrders->first()?->deliveryBoy?->orderRate ?? 0);
    @endphp
    <button class="btn btn-dark btn-sm mx-auto">
        Order's Income: {{ '₹ ' . $total . '/-' ?? 0.0 }}
    </button>
</div>
<table id="example2" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Date</th>
            <th>Order Id</th>
            <th>Order Status</th>
            <th>Delivery Boy</th>
            <th>Payment Type</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody id="">
        @php
            $sr = 1;
            $totalAmount = 0.0;
        @endphp
        @foreach ($monthOrders as $codHistory)
            <tr>
                <td>{{ $sr++ }}</td>
                <td>{{ $codHistory->datetime }}</td>
                <td>{{ $codHistory->order->order_id }}</td>
                <td>{{ $codHistory->order->order_status }}</td>
                <td>{{ $codHistory->deliveryBoy->name }}</td>
                <td>{{ $codHistory->pyment_method }}</td>
                <td>{{ '₹ ' . $codHistory->order->price }}</td>
            </tr>
            @php
                $totalAmount += $codHistory->order->price;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" class="text-center">Total Amount</th>
            <th>{{ $totalAmount . ' ₹' }}</th>
        </tr>
    </tfoot>
</table>
