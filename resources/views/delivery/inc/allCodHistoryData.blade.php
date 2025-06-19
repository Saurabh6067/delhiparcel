@php
    $i = 1;
@endphp
@foreach ($data as $row)
    <tr>
        <td>{{ $i++ }}</td>
        <td>{{ $row->datetime }}</td>
        <td>
            @php
                $deliveryBoy = App\Models\DlyBoy::find($row->delivery_boy_id);
            @endphp
            {{ $deliveryBoy->name ?? 'Transfer to Admin' }}
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

        <td>₹ {{ $row->amount }}</td>
    </tr>
@endforeach