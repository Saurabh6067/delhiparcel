@foreach ($data as $index => $item)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>
            @if (empty($item->assign_to))
                <input type="checkbox" class="row-checkbox" name="assign_order[]" data-id="{{ $item->id }}">
            @else
                -
            @endif
        </td>
        <td class="text-center">
            <a href="{{ url('/delivery-label/' . $item->order_id) }}" title="Order Label" target="_blank"
                class="btn btn-sm btn-success">
                <i class="fas fa-solid fa-file-invoice"></i>
            </a>
        </td>
        <td>{{ $item->order_id }}</td>
        <td>{{ $item->datetime }}</td>
        @if ($item->parcel_type == 'Direct')
            <td>
                <span><b>Name: </b>{{ $item->sender_name }}</span> <br>
                <span><b>Email: </b>{{ $item->sender_email }}</span> <br>
                <span><b>Number: </b>{{ $item->sender_number }}</span> <br>
                <span><b>Address: </b>{{ $item->sender_address }}</span> <br>
            </td>
        @else
            <td>
                <span><b>Name: </b>{{ $item->order->fullname ?? $item->sender_name }}</span> <br>
                <span><b>Email: </b>{{ $item->order->email ?? $item->sender_email }}</span> <br>
                <span><b>Number: </b>{{ $item->order->phoneno ?? $item->sender_number }}</span> <br>
                <span><b>Address: </b>{{ $item->order->fulladdress ?? $item->sender_address }}</span>
            </td>
        @endif
        <td>
            <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
            <span><b>Email: </b>{{ $item->receiver_email }}</span> <br>
            <span><b>Number: </b>{{ $item->receiver_cnumber }}</span> <br>
            <span><b>Address: </b>{{ $item->receiver_add }}</span> <br>
        </td>
        <td>{{ $item->codAmount ?? $item->price }}</td>
        <td>{{ $item->payment_mode }}</td>
        @if ($item->parcel_type == 'Direct')
            <td>{{ $item->sender_pincode }}</td>
        @else
            <td>{{ $item->order->pincode ?? $item->sender_pincode }}</td>
        @endif
        <td>{{ $item->receiver_pincode }}</td>
        <td class="text-capitalize">{{ $item->parcel_type }}</td>
        <td>
            @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                <button class="btn btn-sm btn-warning status" data-id="{{ $item->id }}"
                    title="{{ $item->status_message }}">
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
        <td>
            @if ($item->order_status == 'Delivered' || $item->order_status == 'Cancelled')
                @if ($item->order_status == 'Cancelled')
                    <span class="badge badge-danger" title="{{ $item->status_message }}">NA</span>
                @else
                    <span class="badge badge-success"
                        title="{{ $item->status_message }}">{{ $item->dlyBoy->name ?? '-' }}</span>
                @endif
            @else
                <button class="btn btn-sm btn-secondary assign" data-id="{{ $item->id }}"
                    title="{{ $item->status_message }}">
                    @if ($item->assign_to)
                        <span class="font-weight-bold font-weight-light">
                            {{ $item->dlyBoy->name ?? '-' }}
                        </span>
                    @else
                        Assign To
                    @endif
                </button>
            @endif
        </td>
    </tr>
@endforeach
