@php
    $i = 1;
@endphp
@foreach ($data as $row)
    <tr>
        <td>{{ $i++ }}</td>
        <td>{{ $row->datetime }}</td>
        <td>
            @php
                $branch = App\Models\Branch::find($row->branch_id);
            @endphp
            {{ $branch->fullname ?? 'N/A' }}
        </td>
        <td>
            @if($row->type == 'Received')
                <span class="text-success font-weight-bold">{{ $row->type }}</span>
            @elseif($row->type == 'Debited')
                <span class="text-danger font-weight-bold">{{ $row->type }}</span>
            @else
                {{ $row->type }}
            @endif
        </td>
        <td>{{ $row->remarks }}</td>
        <td>₹ {{ $row->amount }}</td>
    </tr>
@endforeach
<input type="hidden" value="{{ $totalAmount }}" id="totalAmount">