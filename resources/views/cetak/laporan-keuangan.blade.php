<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - {{ $namaBulan }} {{ $tahun }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-green { color: green; font-weight: bold; }
        .text-red { color: red; font-weight: bold; }
        .text-blue { color: blue; font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Keuangan - {{ $namaBulan }} {{ $tahun }}</h2>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $trx)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($trx['tanggal'])->format('d F Y') }}</td>
                    <td>{{ $trx['keterangan'] }}</td>
                    <td class="text-right {{ $trx['tipe'] == 'pendapatan' ? 'text-green' : 'text-red' }}">
                        {{ $trx['tipe'] == 'pendapatan' ? '+' : '-' }} Rp {{ number_format($trx['jumlah'], 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        Rp {{ number_format($trx['jumlah'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-left"><strong>Total Pendapatan</strong></td>
                <td class="text-right text-green">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-left"><strong>Total Pengeluaran</strong></td>
                <td class="text-right text-red">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-left"><strong>Total Saldo</strong></td>
                <td class="text-right text-blue">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>