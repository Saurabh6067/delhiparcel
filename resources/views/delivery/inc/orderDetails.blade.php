@forelse ($data as $index => $item)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>
            @if (empty($item->assign_to))
                @if (!($item->transfer_other_branch == 'false' && !in_array($item->receiver_pincode, explode(',', trim(Session::get('branch_pincodes'), ',')))))
                    <input type="checkbox" class="row-checkbox" name="assign_order[]" data-id="{{ $item->id }}">
                @else
                    -
                @endif
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
        <td>{{ $item->order_id }}
            <br>
            @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                <button class="btn btn-sm btn-warning status" data-id="{{ $item->id }}" title="{{ $item->status_message ?? '' }}">
                    <span class="font-weight-bold font-weight-light">
                        {{ $item->order_status }}
                    </span>
                </button>
            @elseif($item->order_status == 'Delivered')
                <span class="badge badge-success" title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
            @elseif($item->order_status == 'Cancelled')
                <span class="badge badge-danger" title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
            @else
                <span class="badge badge-danger" title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
            @endif
            @if($item->status_message)
                | <span style="font-size:14px">{{ $item->status_message }}</span>
            @endif
        </td>
        <td>{{ $item->datetime }}</td>
        <td>
            @if ($item->service_type == 'ex')
                Express
            @elseif ($item->service_type == 'ss')
                Standard
            @elseif ($item->service_type == 'SuperExpress')
                SuperExpress
            @else
                -
            @endif
        </td>
        @if ($item->parcel_type == 'Direct')
            <td>
                <span><b>Name: </b>{{ $item->sender_name }}</span> <br>
                <span><b>Email: </b>{{ $item->sender_email }}</span> <br>
                <span><b>Number: </b>{{ $item->sender_number }}</span> <br>
                <span><b>Address: </b>{{ $item->sender_address }}</span>
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
            <span><b>Address: </b>{{ $item->receiver_add }}</span>
        </td>
        <td>{{ $item->codAmount ?? '0.00' }}</td>
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
                <button class="btn btn-sm btn-warning status" data-id="{{ $item->id }}" title="{{ $item->status_message ?? '' }}">
                    <span class="font-weight-bold font-weight-light">
                        {{ $item->order_status }}
                    </span>
                </button>
            @elseif($item->order_status == 'Delivered')
                <span class="badge badge-success" title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
            @elseif($item->order_status == 'Cancelled')
                <span class="badge badge-danger" title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
            @else
                <span class="badge badge-danger" title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
            @endif
            @if($item->status_message)
                | <span style="font-size:14px">{{ $item->status_message }}</span>
            @endif
        </td>
        <td>
            @if ($item->order_status == 'Delivered' || $item->order_status == 'Cancelled')
                @if ($item->order_status == 'Cancelled')
                    <span class="badge badge-danger" title="{{ $item->status_message ?? '' }}">NA</span>
                @else
                    <span class="badge badge-success" title="{{ $item->status_message ?? '' }}">{{ $item->dlyBoy->name ?? '-' }}</span>
                @endif
            @else
                @php
                    $branchPincodes = explode(',', trim(Session::get('branch_pincodes'), ','));
                    $isSenderBranch = in_array($item->sender_pincode, $branchPincodes);
                    $isReceiverBranch = in_array($item->receiver_pincode, $branchPincodes);
                    $isSameBranch = $isSenderBranch && $isReceiverBranch;
                @endphp
                @if ($isSameBranch || ($item->transfer_other_branch == 'true' && $isReceiverBranch))
                    <button class="btn btn-sm btn-secondary assign" data-id="{{ $item->id }}"
                        data-other-branch="{{ $item->transfer_other_branch }}" title="{{ $item->status_message ?? '' }}">
                        @if ($item->assign_to)
                            <span class="font-weight-bold font-weight-light">
                                {{ $item->dlyBoy->name ?? '-' }}
                            </span>
                        @else
                            Assign To
                        @endif
                    </button>
                @elseif ($isSenderBranch && !$isReceiverBranch)
                    <span class="badge badge-info">Transferred to other branch</span>
                @else
                    <span class="badge badge-secondary">Not for this branch</span>
                @endif
            @endif
        </td>
        <td> {{ $item->dlyBoy->name ?? '-' }}</td>
    </tr>
@empty
    <tr>
        <td colspan="15" class="text-center">No orders found</td>
    </tr>
@endforelse