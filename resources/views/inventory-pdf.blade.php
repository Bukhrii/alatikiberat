<!DOCTYPE html>
<html>
<head>
    <title>Laporan Inventaris PT Borneo</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .footer { margin-top: 20px; font-style: italic; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN VALUASI ASET INVENTARIS PT Borneo</h2>
        <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th style="text-align: center">Jumlah Item</th>
                <th style="text-align: center">Total Stok</th>
                <th class="text-right">Nilai Aset (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryValuation as $report)
            <tr>
                <td>{{ $report->category }}</td>
                <td style="text-align: center">{{ $report->item_count }}</td>
                <td style="text-align: center">{{ number_format($report->total_stock) }}</td>
                <td class="text-right">Rp {{ number_format($report->total_value, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tr style="font-weight: bold; background: #eee;">
            <td>TOTAL</td>
            <td style="text-align: center">{{ $categoryValuation->sum('item_count') }}</td>
            <td style="text-align: center">{{ number_format($categoryValuation->sum('total_stock')) }}</td>
            <td class="text-right">Rp {{ number_format($categoryValuation->sum('total_value'), 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>