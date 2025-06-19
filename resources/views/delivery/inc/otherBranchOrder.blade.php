@php
    $sr = 1;
@endphp
@foreach ($data as $item)
    <tr>
        <td>{{ $sr++ }}</td>
        <td>
            @if (empty($item->assign_to))
                <input type="checkbox" class="row-checkbox1" name="assign_order[]" data-id="{{ $item->id }}">
            @else
                -
            @endif
        </td>
        <td>
            @if (empty($item->sender_order_pin_by) && !empty($item->assign_to))
                <input type="checkbox" class="row-checkbox2" name="transfer_order[]" data-id="{{ $item->id }}">
            @else
                -
            @endif
        </td>
        <td>{{ $item->order_id }}</td>
        <td>{{ $item->datetime }}</td>
        <td>
            @if ($item->service_type == 'ex')
                Express
            @else
                @if ($item->service_type == 'ss')
                    Standard
                @else
                    @if ($item->service_type == 'SuperExpress')
                        SuperExpress
                    @else
                        -
                    @endif
                @endif
            @endif
        </td>

        {{-- <td>
            <span><b>Name: </b>{{ $item->order->fullname ?? $item->sender_name }}</span> <br>
            <span><b>Email: </b>{{ $item->order->email ?? $item->sender_email }}</span> <br>
            <span><b>Number: </b>{{ $item->order->phoneno ?? $item->sender_number }}</span> <br>
            <span><b>Address: </b>{{ $item->order->fulladdress ?? $item->sender_address }}</span>
        </td>
        <td>
            <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
            <span><b>Email: </b>{{ $item->receiver_email }}</span> <br>
            <span><b>Number: </b>{{ $item->receiver_cnumber }}</span> <br>
            <span><b>Address: </b>{{ $item->receiver_add }}</span> <br>
        </td> --}}

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


        <td>{{ $item->price ?? 0.0 }}</td>
        <td>{{ '₹ ' . $item->codAmount ?? 0.0 }}</td>
        <td>{{ $item->payment_mode }}</td>
        {{-- <td>{{ $item->order->pincode ?? $item->sender_pincode }}</td> --}}
        @if ($item->parcel_type == 'Direct')
            <td>{{ $item->sender_pincode }}</td>
        @else
            <td>{{ $item->order->pincode ?? $item->senderPinCode }}</td>
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
        <td>
            @if ($item->sender_order_pin_by)
                {{ $item->dlyBoy1->name ?? '-' }} | {{ $item->sender_order_status ?? '-' }}
            @endif
        </td>
    </tr>
@endforeach
