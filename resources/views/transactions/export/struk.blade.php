<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur {{ ucfirst($transaction->type) }} - {{ $transaction->reference_number }}</title>
    <!-- Import Poppins from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* PDF Page Setup */
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: 'Poppins', 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 45px;
            background-color: #ffffff;
            color: #334155;
            line-height: 1.4;
        }

        /* Essential Helpers */
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .font-normal { font-weight: 400; }
        .font-semibold { font-weight: 500; }
        .font-bold { font-weight: 700; }
        .uppercase { text-transform: uppercase; }

        /* Brand Header */
        .header {
            display: table;
            width: 100%;
            border-bottom: 1.5px solid #f1f5f9;
            padding-bottom: 20px;
            margin-bottom: 35px;
        }
        .header-left {
            display: table-cell;
            vertical-align: top;
        }
        .header-right {
            display: table-cell;
            vertical-align: top;
            text-align: right;
        }
        .brand-name h1 {
            margin: 0;
            font-size: 22px;
            color: #03235b;
            letter-spacing: -0.5px;
            font-weight: 700;
        }
        .brand-name p {
            margin: 2px 0 0;
            font-size: 10px;
            color: #64748b;
            font-weight: 500;
        }
        .doc-title {
            font-size: 16px;
            color: #0f172a;
            margin: 0;
            font-weight: 700;
        }
        .doc-ref {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* Stakeholder Info - Formal Layout */
        .stakeholders {
            display: table;
            width: 100%;
            margin-bottom: 35px;
        }
        .stakeholder-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .stakeholder-label {
            font-size: 10px;
            color: #94a3b8;
            margin-bottom: 6px;
            font-weight: 700;
        }
        .stakeholder-name {
            font-size: 14px;
            color: #1e293b;
            margin: 0;
            font-weight: 700;
        }
        .stakeholder-info {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Meta Data Grid - Cleaner Blocks */
        .meta-container {
            width: 100%;
            margin-bottom: 35px;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            background-color: #fcfdfe;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 12px 20px;
            border-right: 1px solid #f1f5f9;
        }
        .meta-table td:last-child { border-right: none; }
        .meta-desc {
            font-size: 9px;
            color: #94a3b8;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .meta-text {
            font-size: 11px;
            color: #334155;
            font-weight: 600;
        }

        /* Table Design - Minimalist & Functional */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
        }
        .details-table th {
            padding: 10px 15px;
            border-bottom: 1.5px solid #1e293b;
            font-size: 10px;
            color: #1e293b;
            font-weight: 700;
            text-align: left;
        }
        .details-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11px;
        }
        .row-total {
            font-weight: 700;
            color: #0f172a;
        }

        /* Footer - Unified Summary & Signature */
        .footer-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .signature-section {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        .summary-section {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            padding: 15px 25px;
            background-color: #f8fafc;
            border-radius: 12px;
        }

        /* Summary Styling */
        .sum-row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        .sum-cell-label {
            display: table-cell;
            font-size: 11px;
            color: #64748b;
        }
        .sum-cell-value {
            display: table-cell;
            text-align: right;
            font-size: 11px;
            font-weight: 600;
        }
        .sum-total {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }
        .sum-total .sum-cell-label {
            font-size: 13px;
            color: #0f172a;
            font-weight: 700;
        }
        .sum-total .sum-cell-value {
            font-size: 15px;
            color: #03235b;
            font-weight: 700;
        }

        /* Signature Blocks */
        .sign-table {
            width: 100%;
            margin-top: 40px;
        }
        .sign-cell {
            vertical-align: top;
            text-align: center;
            width: 45%;
        }
        .sign-label {
            font-size: 10px;
            color: #94a3b8;
            font-weight: 700;
        }
        .sign-placeholder {
            height: 70px;
        }
        .sign-line {
            width: 160px;
            border-bottom: 1px solid #cbd5e1;
            margin: 0 auto 6px;
        }
        .sign-name {
            font-size: 11px;
            font-weight: 700;
            color: #334155;
        }

        .status-badge {
            display: inline-block;
            background-color: #ecfdf5;
            color: #059669;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            margin-top: 8px;
        }
        .system-footer {
            margin-top: 40px;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <div class="header-left">
            <div class="brand-name">
                <h1 class="font-bold uppercase">DOMBA LOKA</h1>
                <p class="uppercase">Sistem Manajemen Peternakan Cerdas</p>
            </div>
        </div>
        <div class="header-right">
            <div class="doc-title uppercase">Faktur {{ ucfirst($transaction->type) }}</div>
            <div class="doc-ref">Nomor: {{ $transaction->reference_number }}</div>
        </div>
    </div>

    <!-- Stakeholder Information -->
    <div class="stakeholders">
        <div class="stakeholder-box">
            <div class="stakeholder-label uppercase">Dikeluarkan Oleh</div>
            <div class="stakeholder-name">{{ $transaction->kasir ?? 'Admin Domba Loka' }}</div>
            <div class="stakeholder-info">Operasional Farm Domba Loka</div>
        </div>
        <div class="stakeholder-box">
            <div class="stakeholder-label uppercase">{{ $transaction->type === 'penjualan' ? 'Ditujukan Kepada' : 'Dikirim Oleh' }}</div>
            <div class="stakeholder-name">
                {{ $transaction->type === 'penjualan' ? ($transaction->customer->name ?? '-') : ($transaction->supplier->name ?? '-') }}
            </div>
            <div class="stakeholder-info">
                Telp: {{ $transaction->type === 'penjualan' ? ($transaction->customer->phone ?? '-') : ($transaction->supplier->phone ?? '-') }}
            </div>
        </div>
    </div>

    <!-- Meta Details Block -->
    <div class="meta-container">
        <table class="meta-table">
            <tr>
                <td>
                    <div class="meta-desc uppercase">Tanggal Transaksi</div>
                    <div class="meta-text">{{ $transaction->transaction_date->format('d/m/Y') }}</div>
                </td>
                <td>
                    <div class="meta-desc uppercase">Jatuh Tempo</div>
                    <div class="meta-text">{{ $transaction->due_date->format('d/m/Y') }}</div>
                </td>
                <td>
                    <div class="meta-desc uppercase">Metode Bayar</div>
                    <div class="meta-text">{{ $transaction->payment_method }}</div>
                </td>
                <td>
                    <div class="meta-desc uppercase">Lokasi/Gudang</div>
                    <div class="meta-text">{{ $transaction->warehouse }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Items Details Table -->
    <table class="details-table">
        <thead>
            <tr>
                <th width="40" style="text-align: right;">NO</th>
                <th style="text-align: left;">DESKRIPSI ITEM (KODE & JENIS DOMBA)</th>
                <th width="60" style="text-align: right;">QTY</th>
                <th width="120" style="text-align: right;">HARGA SATUAN</th>
                <th width="80" style="text-align: right;">DISKON</th>
                <th width="140" style="text-align: right;">TOTAL HARGA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->details as $index => $detail)
            <tr>
                <td style="text-align: right;">{{ $index + 1 }}</td>
                <td class="font-semibold" style="text-align: left;">
                    {{ $detail->sheep->code ?? '-' }} &mdash; {{ $detail->sheep->sheepType->name ?? 'Tipe Domba' }}
                </td>
                <td style="text-align: right;">{{ $detail->quantity }} Ekor</td>
                <td style="text-align: right;">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                <td style="text-align: right; @if($detail->discount > 0) font-weight: bold; @endif">
                    @if($detail->discount > 0) {{ $detail->discount }}% @else - @endif
                </td>
                <td class="row-total" style="text-align: right;">Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer Grid (Summary & Signatures) -->
    <div class="footer-grid">
        <div class="signature-section">
            {{-- <table class="sign-table">
                <tr>
                    <td class="sign-cell">
                        <div class="sign-label uppercase">Penerima</div>
                        <div class="sign-placeholder"></div>
                        <div class="sign-line"></div>
                        <div class="sign-name">
                            {{ $transaction->type === 'penjualan' ? ($transaction->customer->name ?? 'Pelanggan') : ($transaction->supplier->name ?? 'Supplier') }}
                        </div>
                    </td>
                    <td width="40"></td>
                    <td class="sign-cell">
                        <div class="sign-label uppercase">Hormat Kami</div>
                        <div class="sign-placeholder"></div>
                        <div class="sign-line"></div>
                        <div class="sign-name">{{ $transaction->kasir ?? 'Admin Domba Loka' }}</div>
                    </td>
                </tr>
            </table> --}}
        </div>

        <div class="summary-section">
            <div class="sum-row">
                <div class="sum-cell-label">Subtotal</div>
                <div class="sum-cell-value">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</div>
            </div>
            
            @if($transaction->tax > 0)
            <div class="sum-row">
                <div class="sum-cell-label">Pajak ({{ $transaction->tax }}%)</div>
                <div class="sum-cell-value">+ Rp {{ number_format($transaction->subtotal * $transaction->tax / 100, 0, ',', '.') }}</div>
            </div>
            @endif
            
            @if($transaction->other_fees > 0)
            <div class="sum-row">
                <div class="sum-cell-label">Biaya Lainnya</div>
                <div class="sum-cell-value">+ Rp {{ number_format($transaction->other_fees, 0, ',', '.') }}</div>
            </div>
            @endif
            
            <div class="sum-row sum-total">
                <div class="sum-cell-label uppercase">Total Tagihan</div>
                <div class="sum-cell-value">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</div>
            </div>

            @if($transaction->downpayment > 0)
            <div class="sum-row" style="margin-top: 8px;">
                <div class="sum-cell-label">Dibayar / DP</div>
                <div class="sum-cell-value" style="color: #059669;">- Rp {{ number_format($transaction->downpayment, 0, ',', '.') }}</div>
            </div>
            @endif

            @if($transaction->sisa > 0)
            <div class="sum-row" style="margin-top: 5px; padding-top: 10px; border-top: 1px dashed #cbd5e1;">
                <div class="sum-cell-label font-bold">SISA TAGIHAN</div>
                <div class="sum-cell-value font-bold" style="color: #ef4444;">Rp {{ number_format($transaction->sisa, 0, ',', '.') }}</div>
            </div>
            @else
            <div class="text-right">
                <div class="status-badge uppercase">LUNAS</div>
            </div>
            @endif
        </div>
    </div>

    <div class="system-footer">
        * Dokumen ini diterbitkan secara otomatis oleh sistem administrasi Domba Loka. Tidak memerlukan tanda tangan basah untuk validitas digital.
    </div>

</body>
</html>
