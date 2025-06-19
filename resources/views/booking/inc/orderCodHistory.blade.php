@foreach ($OrderCod as $key => $value)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $value->d_amount ?? '-' }}</td>
        <td>{{ $value->refno ?? '-' }}</td>
        <td>{{ $value->datetime ?? '-' }}</td>
    </tr>
@endforeach
