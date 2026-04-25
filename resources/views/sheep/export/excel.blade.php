@php
    use PhpOffice\PhpSpreadsheet\Shared\Color;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Border;
    use PhpOffice\PhpSpreadsheet\Style\Font;
@endphp
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kode Domba</th>
            <th>Jenis Kelamin</th>
            <th>Jenis Domba</th>
            <th>Berat Awal (kg)</th>
            <th>Berat Aktual (kg)</th>
            <th>Selisih Berat (kg)</th>
            <th>Target (kg)</th>
            <th>Kondisi</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($growths as $i => $g)
            @php
                $statusColor = $g->weight >= $g->target ? 'success' : 'danger';
                $statusLabel = $g->weight >= $g->target ? 'Mencapai Target' : 'Belum Mencapai Target';
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($g->date)->format('d/m/Y') }}</td>
                <td style="font-weight: bold; color: #1e40af;">{{ $g->sheep->code ?? '-' }}</td>
                <td>{{ $g->sheep->gender === 'male' ? 'Jantan' : 'Betina' }}</td>
                <td>{{ $g->sheep->sheepType->name ?? '-' }}</td>
                <td>{{ number_format($g->previous_weight, 1) }}</td>
                <td style="font-weight: bold;">{{ number_format($g->actual_weight, 1) }}</td>
                <td>{{ number_format($g->weight, 1) }}</td>
                <td>{{ number_format($g->target, 1) }}</td>
                <td>{{ $g->note ?? '-' }}</td>
                <td style="background: {{ $statusColor === 'success' ? '#dcfce7' : '#fee2e2' }}; color: {{ $statusColor === 'success' ? '#166534' : '#991b1b' }};">
                    {{ $statusLabel }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>