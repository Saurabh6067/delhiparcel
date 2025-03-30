@php
    $sr = 1;
@endphp
@foreach ($codHistory as $cod)
    <tr>
        <td>{{ $sr++ }}</td>
        <td>{{ $cod->amount }}</td>
        <td>{{ $cod->datetime }}</td>
        <td>{{ $cod->users->name ?? $cod->branch->fullname }}</td>
    </tr>
@endforeach
