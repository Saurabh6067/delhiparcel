@php
    $sr = 1;
@endphp
@foreach ($data as $item)
    <tr>
        <td>{{ $sr++ }}</td>
        <td>{{ $item->order_id }}</td>
        <td>{{ $item->datetime }}</td>
        <td>
            <span><b>Name: </b>{{ $item->order->fullname }}</span> <br>
            <span><b>Email: </b>{{ $item->order->email }}</span> <br>
            <span><b>Number: </b>{{ $item->order->phoneno }}</span> <br>
            <span><b>Address: </b>{{ $item->order->fulladdress }}</span>
        </td>
        <td>
            <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
            <span><b>Email: </b>{{ $item->receiver_email }}</span> <br>
            <span><b>Number: </b>{{ $item->receiver_cnumber }}</span> <br>
            <span><b>Address: </b>{{ $item->receiver_add }}</span> <br>
        </td>
        <td>{{ $item->price }}</td>
        <td>{{ $item->payment_mode }}</td>
        <td>{{ $item->order->pincode }}</td>
        <td>{{ $item->receiver_pincode }}</td>
        <td>
            @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                <button class="btn btn-sm btn-warning" data-id="{{ $item->id }}" title="{{ $item->status_message }}">
                    <span class="font-weight-bold font-weight-light ">
                        {{ $item->order_status }}
                    </span>
                </button>
            @elseif($item->order_status == 'Delivered')
                <span class="badge badge-success" title="{{ $item->status_message }}">{{ $item->order_status }}</span>
            @elseif($item->order_status == 'Cancelled')
                <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}</span>
            @else
                <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}</span>
            @endif
        </td>
    </tr>
@endforeach
