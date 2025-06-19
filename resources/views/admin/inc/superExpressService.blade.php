@php
    $sr = 1;
@endphp
@foreach ($data as $value)
    <tr>
        <td>{{ $sr++ }}</td>
        <td>{{ $value->title }}</td>
        <td>{{ $value->price }}</td>
        <td>
            <div class="form-group">
                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                    <input type="checkbox" class="custom-control-input status-toggle" id="status{{ $value->id }}"
                        @if ($value->status == 'active') checked @endif data-id="{{ $value->id }}">
                    <label class="custom-control-label" for="status{{ $value->id }}"></label>
                </div>
            </div>
        </td>
        <td>
            @if ($value->type == 'se')
                <span class="badge badge-success">Regular</span>
            @else
                <span class="badge badge-dark">Special Price</span>
            @endif
        </td>
        <td>
            <a href="{{ url('/super-express/' . $value->id) }}" class="btn btn-sm btn-dark"><i
                    class="fas fa-edit"></i></a>
            <a href="{{ url('/delete-services/' . $value->id) }}" class="btn btn-sm btn-danger"><i
                    class="fas fa-trash"></i></a>
        </td>
    </tr>
@endforeach
