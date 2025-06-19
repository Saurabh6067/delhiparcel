@foreach ($data as $key => $item)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $item->order_id }}</td>
        <td>{{ $item->datetime }}</td>
       
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
          
       
       
        
        <td>{{ $item->sender_pincode ?? '' }}</td>
        
        <td>{{ $item->receiver_pincode ?? $item->receiverPinCode }}</td>
        {{-- <td class="text-capitalize">{{ $item->parcel_type ?? 'Direct' }}</td> --}}
        <td>
            @if ($item->order_status == 'Booked' || $item->order_status == 'Item Picked Up')
                <a href="tel:{{ $item->sender_number }}" class="btn btn-sm btn-success">Call Now</a>
            @else
                <a href="tel:{{ $item->receiver_cnumber }}" class="btn btn-sm btn-success">Call Now</a>
            @endif
        </td>
        <td>
            @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                <button class="btn btn-sm btn-warning status" data-id="{{ $item->id }}" title="{{ $item->status_message }}"
                    data-action="{{ request()->segment(2) }}">
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