@php
    $sr = 1;
@endphp
@foreach ($data as $item)
    <tr>
        <td>{{ $sr++ }}</td>
        <td class="text-center">
            <a href="{{ url('/admin-label/' . $item->order_id) }}" title="Order Label" target="_blank"
                class="btn btn-sm btn-success">
                <i class="fas fa-solid fa-file-invoice"></i>
            </a>
            <br/>
             {{ $item->dlyBoy->name ?? '-' }}
        </td>
        <td>
            {{ $item->order_id }}
            <br>
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
            | <span style="font-size:14px">{{ $item->status_message ?? '' }}</span>
        </td>
        <td>{{ $item->datetime }}</td>
        @if ($item->parcel_type == 'delivery' || $item->parcel_type == 'Direct')
            @if ($item->parcel_type == 'Direct')
                <td>
                    <span><b>Name: </b>{{ $item->sender_name ?? '-' }}</span> <br>
                    <span><b>Email: </b>{{ $item->sender_email ?? '-' }}</span> <br>
                    <span><b>Number: </b>{{ $item->sender_number ?? '-' }}</span> <br>
                    <span><b>Address: </b>{{ $item->sender_address ?? '-' }}</span> <br>
                </td>
            @else
                <td>
                    <span><b>Name: </b>{{ $item->order->fullname ?? '-' }}</span> <br>
                    <span><b>Email: </b>{{ $item->order->email ?? '-' }}</span> <br>
                    <span><b>Number: </b>{{ $item->order->phoneno ?? '-' }}</span> <br>
                    <span><b>Address: </b>{{ $item->order->fulladdress ?? '-' }}</span>
                </td>
            @endif
            <td>
                <span><b>Name: </b>{{ $item->receiver_name ?? '-' }}</span> <br>
                <span><b>Email: </b>{{ $item->receiver_email ?? '-' }}</span> <br>
                <span><b>Number: </b>{{ $item->receiver_cnumber ?? '-' }}</span> <br>
                <span><b>Address: </b>{{ $item->receiver_add ?? '-' }}</span> <br>
            </td>
        @else
            <td>
                <span><b>Name: </b>{{ $item->receiver_name ?? '-' }}</span> <br>
                <span><b>Email: </b>{{ $item->receiver_email ?? '-' }}</span> <br>
                <span><b>Number: </b>{{ $item->receiver_cnumber ?? '-' }}</span> <br>
                <span><b>Address: </b>{{ $item->receiver_add ?? '-' }}</span> <br>
            </td>
            <td>
                <span><b>Name: </b>{{ $item->order->fullname ?? '-' }}</span> <br>
                <span><b>Email: </b>{{ $item->order->email ?? '-' }}</span> <br>
                <span><b>Number: </b>{{ $item->order->phoneno ?? '-' }}</span> <br>
                <span><b>Address: </b>{{ $item->order->fulladdress ?? '-' }}</span>
            </td>
        @endif

        <td>{{ $item->price }}</td>
        <td>{{ $item->codAmount }}</td>
        <td>
        @if($item->payment_mode == 'online')
            Prepaid
        @elseif($item->payment_mode == 'COD')
            COD
        @else
            Unknown
        @endif
        </td>
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
        @if ($item->parcel_type == 'Direct')
            <td>{{ $item->sender_pincode ?? '-' }}</td>
        @else
            <td>{{ $item->order->pincode ?? '-' }}</td>
        @endif
        <td>{{ $item->receiver_pincode ?? '-' }}</td>
        <td class="text-capitalize">{{ $item->parcel_type ?? '-' }}</td>
        <td>
            {{-- @if ($item->order_status == 'Booked') --}}
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
