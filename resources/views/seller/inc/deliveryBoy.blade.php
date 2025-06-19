@php
    $sr = 1;
@endphp
@foreach ($data as $item)
    <tr>
        <td>{{ $sr++ }}</td>
        <td>{{ $item->order_id }}</td>
        <td class="text-center">
            <a href="{{ url('/seller-invoice/' . $item->order_id) }}" title="Order Invoice" target="_blank"
                class="btn btn-sm btn-danger">
                <i class="fas fa-solid fa-file-invoice"></i>
            </a>
        </td>
        <td class="text-center">
            <a href="{{ url('/seller-label/' . $item->order_id) }}" title="Order Label" target="_blank"
                class="btn btn-sm btn-success">
                <i class="fas fa-solid fa-file-invoice"></i>
            </a>
        </td>
        <td class="text-capitalize">{{ $item->parcel_type }}</td>
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
                @if ($item->order_status == 'Booked')
                    <button class="btn btn-sm btn-primary edit" data-id="{{ $item->id }}">
                        <i class="fas fa-edit text-white"></i>
                    </button>
                    <a href="{{ url('/seller-cancelled-order/' . $item->id) }}" class="btn btn-sm btn-danger">
                        Cancelled Order
                    </a>
                @else
                    --
                @endif
            </td>
        @else
            <td>
                <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
                <span><b>Email: </b>{{ $item->receiver_email }}</span> <br>
                <span><b>Number: </b>{{ $item->receiver_cnumber }}</span> <br>
                <span><b>Address: </b>{{ $item->receiver_add }}</span> <br>
                @if ($item->order_status == 'Booked')
                    <button class="btn btn-sm btn-primary edit" data-id="{{ $item->id }}">
                        <i class="fas fa-edit text-white"></i>
                    </button>
                    <a href="{{ url('/booking-cancelled-order/' . $item->id) }}" class="btn btn-sm btn-danger">
                        Cancelled Order
                    </a>
                @else
                    --
                @endif
            </td>
            <td>
                <span><b>Name: </b>{{ $item->order->fullname }}</span> <br>
                <span><b>Email: </b>{{ $item->order->email }}</span> <br>
                <span><b>Number: </b>{{ $item->order->phoneno }}</span> <br>
                <span><b>Address: </b>{{ $item->order->fulladdress }}</span>
            </td>
        @endif
        <td>{{ $item->order->pincode }}</td>
        <td>{{ $item->receiver_pincode }}</td>
        <td>
            @if ($item->service_type == 'ss')
                <span class="font-weight-bold">Standard </span>
            @elseif ($item->service_type == 'ex')
                <span class="font-weight-bold">Express</span>
            @else
                <span class="font-weight-bold">Super Express</span>
            @endif
        </td>
        <td>{{ $item->service_title }}</td>
        <td>{{ $item->service_price }}</td>
        <td>
            @if ($item->insurance == 'Yes')
                <span class="btn btn-sm btn-success">Yes</span>
            @else
                <span class="btn btn-sm btn-danger">NO</span>
            @endif
        </td>
        <td>
            @if ($item->payment_mode == 'online')
                <span class="btn btn-sm btn-success">{{ $item->payment_mode }}</span>
            @else
                <span class="btn btn-sm btn-danger">{{ $item->payment_mode }}</span>
            @endif
        </td>
        <td>{{ $item->price }}</td>
        <td>
            @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                <button class="btn btn-sm btn-warning" data-id="{{ $item->id }}"
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
        {{-- <td>
            <button class="btn btn-sm btn-success status" data-id="{{ $item->id }}">
                {{ $item->order_status }}
                @if ($item->order_status)
                    <span class="font-weight-bold font-weight-light" title="{{ $item->status_message }}">
                        - {{ $item->order_status }}
                    </span>
                @endif
            </button>
        </td> --}}
        {{-- <td>
            <button class="btn btn-sm btn-secondary assign" data-id="{{ $item->id }}">
                Assign To
                @if ($item->assign_to)
                    <span class="font-weight-bold font-weight-light" title="{{ $item->status_message }}">
                        - {{ $item->dlyBoy->name }}
                    </span>
                @endif
            </button>
        </td> --}}
        <td>
            {{ $item->datetime }}
        </td>
        <td>
            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
@endforeach
