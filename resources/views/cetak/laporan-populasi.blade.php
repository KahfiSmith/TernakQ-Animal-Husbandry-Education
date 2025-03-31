<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Populasi Ayam</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h2 style="text-align: center;">Laporan Manajemen Ayam</h2>
    <p><strong>Kode Populasi:</strong> {{ $populasi->kode_batch }}</p>
    <p><strong>Nama Populasi:</strong> {{ $populasi->nama_batch }}</p>
    <p><strong>Tanggal DOC:</strong> {{ \Carbon\Carbon::parse($populasi->tanggal_doc)->translatedFormat('d F Y') }}</p>
    <p><strong>Jumlah Ayam Masuk:</strong> {{ $populasi->jumlah_ayam_masuk }}</p>
    <p><strong>Status Ayam:</strong> {{ $populasi->status_ayam }}</p>

    <h3>Data Harian Ayam</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Input</th>
                <th>Jumlah Ayam Sakit</th>
                <th>Jumlah Ayam Mati</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($populasi->harianAyam as $index => $harian)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($harian->tanggal_input)->translatedFormat('d F Y') }}</td>
                    <td>{{ $harian->jumlah_ayam_sakit }}</td>
                    <td>{{ $harian->jumlah_ayam_mati }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
