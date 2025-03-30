@foreach ($data as $key => $value)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $value->order_id }}</td>
        <td class="text-center">
            <a href="{{ url('/admin-invoice/' . $value->order_id) }}" title="Order Invoice" target="_blank"
                class="btn btn-sm btn-danger">
                <i class="fas fa-solid fa-file-invoice"></i>
            </a>
        </td>
        <td class="text-center">
            <a href="{{ url('/admin-label/' . $value->order_id) }}" title="Order Label" target="_blank"
                class="btn btn-sm btn-success">
                <i class="fas fa-solid fa-file-invoice"></i>
            </a>
        </td>
        <td>
            @if ($value->service_type == 'ss')
                <span class="font-weight-bold">Standard </span>
            @elseif ($value->service_type == 'ex')
                <span class="font-weight-bold">Express</span>
            @else
                <span class="font-weight-bold">Super Express</span>
            @endif
        </td>
        <td>{{ $value->pickupAddress }}</td>
        <td>{{ $value->deliveryAddress }}</td>
        <td>
            <span><b>Name: </b>{{ $value->sender_name }}</span> <br>
            <span><b>Number: </b>{{ $value->sender_number }}</span> <br>
            <span><b>Email: </b>{{ $value->sender_email }}</span> <br>
            <span><b>Address: </b>{{ $value->sender_address }}</span> <br>
        </td>
        <td>
            <span><b>Name: </b>{{ $value->receiver_name }}</span> <br>
            <span><b>Number: </b>{{ $value->receiver_cnumber }}</span> <br>
            <span><b>Email: </b>{{ $value->receiver_email }}</span> <br>
            <span><b>Address: </b>{{ $value->receiver_add }}</span> <br>
        </td>
        <td>{{ $value->sender_pincode }}</td>
        <td>{{ $value->receiver_pincode }}</td>
        <td>
            @if ($value->service_id !== 'SuperExpress')
                {{ $value->service->title ?? $value->service_title }}
            @else
                {{ $value->service_id }}
            @endif
        </td>
        <td>{{ $value->price }}</td>
        <td>{{ $value->codAmount }}</td>
        <td> <span class="badge badge-success text-uppercase">{{ $value->payment_mode }}</span></td>
        <td>
            @if ($value->order_status !== 'Delivered' && $value->order_status !== 'Cancelled')
                <button class="btn btn-sm btn-warning status" data-id="{{ $value->id }}"
                    title="{{ $value->status_message }}">
                    <span class="font-weight-bold font-weight-light ">
                        {{ $value->order_status }}
                    </span>
                </button>
            @elseif($value->order_status == 'Delivered')
                <span class="badge badge-success"
                    title="{{ $value->status_message }}">{{ $value->order_status }}</span>
            @elseif($value->order_status == 'Cancelled')
                <span class="badge badge-danger"
                    title="{{ $value->status_message }}">{{ $value->order_status }}</span>
            @else
                <span class="badge badge-danger"
                    title="{{ $value->status_message }}">{{ $value->order_status }}</span>
            @endif
        </td>
        <td>
            @if ($value->order_status == 'Delivered' || $value->order_status == 'Cancelled')
                @if ($value->order_status == 'Cancelled')
                    <span class="badge badge-danger" title="{{ $value->status_message }}">NA</span>
                @else
                    <span class="badge badge-success"
                        title="{{ $value->status_message }}">{{ $value->dlyBoy->name ?? '-' }}</span>
                @endif
            @else
                <button class="btn btn-sm btn-secondary assign" data-id="{{ $value->id }}"
                    title="{{ $value->status_message }}">
                    @if ($value->assign_to)
                        <span class="font-weight-bold font-weight-light">
                            {{ $value->dlyBoy->name ?? '-' }}
                        </span>
                    @else
                        Assign To
                    @endif
                </button>
            @endif
        </td>
        <td>
            @if (!empty($value->insurance))
                <span class="btn btn-sm btn-success">Yes</span>
            @else
                <span class="btn btn-sm btn-danger">NO</span>
            @endif
        </td>
        <td>{{ $value->datetime }}</td>
    </tr>
@endforeach
