<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pertumbuhan Domba</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            background: #ffffff;
            line-height: 1.5;
        }

        /* Layout Helpers for DomPDF (no flexbox) */
        .w-full {
            width: 100%;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .align-top {
            vertical-align: top;
        }

        .align-middle {
            vertical-align: middle;
        }

        /* Header */
        .header-container {
            margin-bottom: 25px;
            border-bottom: 2px solid #1d4ed8;
            padding-bottom: 15px;
            padding-top: 20px;
            padding-left: 30px;
            padding-right: 30px;
        }

        .header-table {
            width: 100%;
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .header-subtitle {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
        }

        .header-meta {
            font-size: 10px;
            color: #475569;
            line-height: 1.6;
        }

        .header-meta strong {
            color: #1e293b;
        }

        /* Stats */
        .stats-container {
            margin: 0 30px 25px;
        }

        .stats-table {
            width: 100%;
            border-spacing: 10px;
            border-collapse: separate;
            margin-left: -10px;
            /* Offset spacing */
            margin-right: -10px;
        }

        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            width: 25%;
        }

        .stat-val {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .stat-lbl {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Filter */
        .filter-container {
            margin: 0 30px 20px;
            background: #f1f5f9;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 10px;
            color: #334155;
            border-left: 3px solid #3b82f6;
        }

        /* Main Table */
        .table-wrap {
            margin: 0 30px 30px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
        }

        .data-table thead tr {
            background: #1e3a8a;
        }

        .data-table thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            border: 1px solid #1e3a8a;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .data-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .data-table tbody td {
            padding: 9px 12px;
            border: 1px solid #cbd5e1;
            font-size: 12px;
            color: #334155;
            vertical-align: middle;
        }

        /* Badges & Colors */
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .text-success {
            color: #15803d;
            font-weight: 600;
        }

        .text-danger {
            color: #dc2626;
            font-weight: 600;
        }



        .font-bold {
            font-weight: 700;
        }

        /* Footer */
        .footer {
            margin: 0 30px;
            padding-top: 15px;
            border-top: 1px solid #cbd5e1;
            font-size: 9px;
            color: #64748b;
        }

        .footer-table {
            width: 100%;
        }

        /* Paging */
        @page {
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td class="align-middle text-left" style="width: 8%; padding-bottom: 5px;">
                    <img src="{{ public_path('images/domba.png') }}" style="height: 50px; width: auto;" alt="Logo">
                </td>
                <td class="align-middle text-left" style="width: 52%; padding-left: 15px;">
                    <div style="font-size: 25px; font-weight: 800; letter-spacing: 0.5px;">DOMBA LOKA
                    </div>
                    <div class="header-title" style="font-size: 18px; color: #0f172a;">LAPORAN
                        PERTUMBUHAN DOMBA</div>
                    <div class="header-subtitle">Daftar Pemantauan Berat & Perkembangan Ternak</div>
                </td>
                <td class="align-middle text-right header-meta" style="width: 40%;">
                    <div><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}</div>
                    {{-- <div><strong>Total Data:</strong> {{ $growths->count() }} record</div> --}}
                    @if (!empty($filters['date_range']))
                        <div><strong>Periode:</strong> {{ $filters['date_range'] }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @php
        $totalRecords = $growths->count();
        $reached = $growths->filter(fn($g) => $g->weight >= $g->target)->count();
        $notReached = $totalRecords - $reached;
        $avgGain = $totalRecords > 0 ? $growths->avg('weight') : 0;
    @endphp



    @if (array_filter($filters))
        <div class="filter-container">
            <strong>Filter Aktif:</strong>
            @if ($filters['date_range'] ?? null)
                Tanggal: <strong>{{ $filters['date_range'] }}</strong> &nbsp;|&nbsp;
            @endif
            @if ($filters['status'] ?? null)
                Status: <strong>{{ $filters['status'] === 'reached' ? 'Target Tercapai' : 'Belum Tercapai' }}</strong>
            @endif
        </div>
    @endif

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">No</th>
                    <th class="text-center">Tanggal</th>
                    <th>Kode Domba</th>
                    <th>Jenis</th>
                    <th class="text-right">Berat Awal</th>
                    <th class="text-right">Berat Aktual</th>
                    <th class="text-right">Kenaikan</th>
                    <th class="text-right">Target</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($growths as $i => $g)
                    @php
                        $isReached = $g->weight >= $g->target;
                        $gain = $g->weight;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($g->date)->format('d/m/Y') }}</td>
                        <td class="font-bold" style="color: #1e40af;">{{ $g->sheep->code ?? '-' }}</td>
                        <td>{{ $g->sheep->sheepType->name ?? '-' }}</td>
                        <td class="text-right">{{ number_format($g->previous_weight, 1) }} kg</td>
                        <td class="text-right font-bold">{{ number_format($g->actual_weight, 1) }} kg</td>
                        <td class="text-right {{ $gain >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $gain >= 0 ? '+' : '' }}{{ number_format($gain, 1) }} kg
                        </td>
                        <td class="text-right">{{ number_format($g->target, 1) }} kg</td>
                        <td class="text-center">
                            <span class="status-badge {{ $isReached ? 'badge-success' : 'badge-danger' }}">
                                {{ $isReached ? 'Tercapai' : 'Belum Tercapai' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center"
                            style="padding: 30px; font-style: italic; color: #94a3b8;">
                            Tidak ada data pemantauan pertumbuhan untuk kriteria ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="stats-container">
        <table class="stats-table">
            <tr>
                <td class="stat-box">
                    <div class="stat-val">{{ $totalRecords }}</div>
                    <div class="stat-lbl">Total Record</div>
                </td>
                <td class="stat-box">
                    <div class="stat-val" style="color: #15803d;">{{ $reached }}</div>
                    <div class="stat-lbl">Target Tercapai</div>
                </td>
                <td class="stat-box">
                    <div class="stat-val" style="color: #dc2626;">{{ $notReached }}</div>
                    <div class="stat-lbl">Belum Tercapai</div>
                </td>
                <td class="stat-box">
                    <div class="stat-val">{{ number_format($avgGain, 1) }} kg</div>
                    <div class="stat-lbl">Rata-rata Kenaikan</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="text-left"><strong>DOMBA LOKA</strong> - Sistem Manajemen Ternak Profesional</td>
                <td class="text-right">Di-generate otomatis pada {{ now()->format('d F Y H:i:s') }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
