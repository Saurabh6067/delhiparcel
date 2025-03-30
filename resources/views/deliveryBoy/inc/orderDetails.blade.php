@foreach ($data as $key => $item)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $item->order_id }}</td>
        <td>{{ $item->datetime }}</td>
        @if ($item->parcel_type == 'delivery')
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
        @else
            @if ($item->parcel_type == 'Pickup')
                <td>
                    <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
                    <span><b>Email: </b>{{ $item->receiver_email }}</span> <br>
                    <span><b>Number: </b>{{ $item->receiver_cnumber }}</span> <br>
                    <span><b>Address: </b>{{ $item->receiver_add }}</span> <br>
                </td>
                <td>
                    <span><b>Name: </b>{{ $item->order->fullname ?? $item->sender_name }}</span> <br>
                    <span><b>Email: </b>{{ $item->order->email ?? $item->sender_email }}</span> <br>
                    <span><b>Number: </b>{{ $item->order->phoneno ?? $item->sender_number }}</span> <br>
                    <span><b>Address: </b>{{ $item->order->fulladdress ?? $item->sender_address }}</span>
                </td>
            @else
                <td>
                    <span><b>Name: </b>{{ $item->sender_name }}</span> <br>
                    <span><b>Number: </b>{{ $item->sender_number }}</span> <br>
                    <span><b>Email: </b>{{ $item->sender_email }}</span> <br>
                    <span><b>Address: </b>{{ $item->sender_address }}</span> <br>
                </td>
                <td>
                    <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
                    <span><b>Number: </b>{{ $item->receiver_cnumber }}</span> <br>
                    <span><b>Email: </b>{{ $item->receiver_email }}</span> <br>
                    <span><b>Address: </b>{{ $item->receiver_add }}</span> <br>
                </td>
            @endif
        @endif
        {{-- <td>{{ $item->price }}</td> --}}
        {{-- <td class="text-uppercase">{{ $item->payment_mode ?? $item->payment_methods }}</td> --}}
        @if ($item->parcel_type == 'Direct')
            <td>{{ $item->sender_pincode }}</td>
        @else
            <td>{{ $item->order->pincode ?? $item->senderPinCode }}</td>
        @endif
        <td>{{ $item->receiver_pincode ?? $item->receiverPinCode }}</td>
        {{-- <td class="text-capitalize">{{ $item->parcel_type ?? 'Direct' }}</td> --}}
        <td>
            <a href="tel:{{ $item->receiver_cnumber }}" class="btn btn-sm btn-success">Call Now</a>
        </td>
        <td>
            @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                <button class="btn btn-sm btn-warning status" data-id="{{ $item->id }}"
                    title="{{ $item->status_message }}" data-action="{{ request()->segment(2) }}">
                    <span class="font-weight-bold font-weight-light ">
                        {{ $item->order_status }} | {{ $item->status_message ?? '-' }}
                    </span>
                </button>
            @elseif($item->order_status == 'Delivered')
                <span class="badge badge-success" title="{{ $item->status_message }}">{{ $item->order_status }}
                    | {{ $item->status_message ?? '-' }}</span>
            @elseif($item->order_status == 'Cancelled')
                <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}
                    | {{ $item->status_message ?? '-' }}</span>
            @else
                <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}
                    | {{ $item->status_message ?? '-' }}</span>
            @endif
        </td>
    </tr>
@endforeach
