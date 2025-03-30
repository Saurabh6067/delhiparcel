@foreach ($data as $key => $item)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $item->fullname }}</td>
        <td>{{ $item->email }}</td>
        <td>{{ $item->phoneno }}</td>
        <td>{{ $item->itemno }}</td>
        <td>{{ $item->gst_panno }}</td>
        <td>
            <div id="lightgallery">
                <a href="{{ asset($item->gst_panno_img) }}" data-lg-size="1600-2400" target="_blank">
                    <img src="{{ asset($item->gst_panno_img) }}" alt="{{ $item->gst_panno }}" height="47">
                </a>
            </div>
        </td>
        <td>{{ $item->cat->cat_name ?? '-' }}</td>
        <td>{{ $item->fulladdress }}</td>
        <td>{{ $item->message }}</td>
        <td>{{ $item->pinCode }}</td>
        <td>
            @if ($item->status == 'active')
                <span class="badge badge-success">Active</span>
            @else
                <button class="btn btn-outline-danger badge" id="status"
                    data-id="{{ $item->id }}">Inactive</button>
            @endif
        </td>
        <td>
            @if ($item->status != 'active')
                <a href="{{ url('edit-enquiry') . '/' . $item->id }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
            @endif
            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
@endforeach
