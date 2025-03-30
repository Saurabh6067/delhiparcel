@php
    $sr = 1;
@endphp
@foreach ($data as $item)
    <tr>
        <td>{{ $sr++ }}</td>
        <td>{{ $item->datetime }}</td>
        <td>{{ $item->c_amount ?? '-' }}</td>
        <td>{{ $item->d_amount ?? '-' }}</td>
        <td>{{ $item->total }}</td>
        <td class="text-uppercase">
            @if (!empty($item->adminid))
                {{ $item->users->type . '/' . $item->msg }}
            @else
                @if ($item->msg == 'credit')
                    Credit
                @elseif($item->msg == 'credit')
                    Debit
                @else
                    {{ $item->msg }}
                @endif
            @endif
        </td>
        <td>
            @if ($item->status == 'success')
                <span class="font-weight-bold text-success">{{ $item->status }}</span>
            @elseif ($item->status == 'pending')
                <span class="font-weight-bold text-warning">{{ $item->status }}</span>
            @else
                <span class="font-weight-bold text-danger">{{ $item->status }}</span>
            @endif
        </td>
        <td>{{ $item->refno ?? '-' }}</td>
    </tr>
@endforeach
