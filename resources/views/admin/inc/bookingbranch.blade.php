@php
    $sr = 1;
@endphp
@foreach ($data as $item)
    <tr>
        <td>{{ $sr++ }}</td>
        <td>
            @if ($item->type != 'Delivery')
                <nav aria-label="...">
                    <ul class="pagination pagination-sm">
                        <li class="page-item"><a class="page-link" href="{{ url('/branch-Manage-Branch/' . $item->id) }}"
                                title="Manage Branch">
                                <i class="fas fa-solid fa-layer-group"></i>
                            </a>
                        </li>
                        {{-- <li class="page-item"><a class="page-link" href="{{ url('/branch-AllDeliveryBoy/' . $item->id) }}"
                            title="Manage Delivery Boy">
                            <i class="fas fa-solid fa-users"></i>
                        </a>
                    </li> --}}
                        <li class="page-item">
                            <a class="page-link" href="{{ url('/manage-services-type/' . $item->id) }}"
                                title="Manage Services Type">
                                <i class="fas fa-solid fa-tag"></i>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ url('/admin-branch-COD-History/' . $item->id) }}"
                                title="Branch COD History">
                                <i class="fas fa-solid fa-money-bill"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            @else
                --
            @endif
        </td>
        <td>
            <div class="form-group">
                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                    <input type="checkbox" class="custom-control-input status-toggle" id="status{{ $item->id }}"
                        @if ($item->status == 'active') checked @endif data-id="{{ $item->id }}">
                    <label class="custom-control-label" for="status{{ $item->id }}"></label>
                </div>
            </div>
        </td>
        <td>{{ $item->branch_total_cod->amount ?? ''}}</td>  <!-- Total Cod Amount -->
        <td>{{ $item->fullname }}</td>
        <td>{{ $item->email }}</td>
        <td>{{ $item->fulladdress }}</td>
        <td>{{ $item->itemcount }}</td>
        <td>{{ $item->phoneno }}</td>
        <td>{{ $item->cat->cat_name ?? '-' }}</td>
        <td>{{ $item->gst_panno }}</td>
        <td>
            <div id="lightgallery">
                <a href="{{ asset($item->gst_panno_img) }}" data-lg-size="1600-2400" target="_blank">
                    <img src="{{ asset($item->gst_panno_img) }}" alt="{{ $item->gst_panno }}" height="47">
                </a>
            </div>
        </td>
        <td>{{ $item->pincode }}</td>
        <td>{{ $item->type }}</td>
        <td>
            <div id="lightgallery">
                <a href="{{ asset($item->type_logo) }}" data-lg-size="1600-2400" target="_blank">
                    <img src="{{ $item->type_logo }}" alt="{{ $item->type_logo }}" height="47">
                </a>
            </div>
        </td>
        <td>{{ $item->password }}</td>
        {{-- <td>
            {{ $item->branch_cm ?? 'NA' }}
        </td> --}}
        <td>
            <a href="{{ url('/admin-booking-branch/' . $item->id) }}" class="btn btn-sm btn-dark"><i
                    class="fas fa-edit"></i></a>
            <a href="{{ url('/delete-branch/' . $item->id) }}" class="btn btn-sm btn-danger"><i
                    class="fas fa-trash"></i></a>
        </td>
    </tr>
@endforeach
