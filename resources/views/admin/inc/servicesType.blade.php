@if ($data->count() > 0)
    @php $sr = 1; @endphp
    @foreach ($data as $value)
        <tr>
            <td>{{ $sr++ }}</td>
            <td>{{ $value->title }}</td>
            <td>{{ number_format($value->price, 2) }}</td>
            <td>
                @if ($value->type == 'ss' || $value->type == 'ex' || $value->type == 'se')
                    -
                @else
                    <input type="checkbox" name="servicesId[]" value="{{ $value->id }}">
                @endif
            </td>
           
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center">No data found</td>
    </tr>
@endif
