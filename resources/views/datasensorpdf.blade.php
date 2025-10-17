<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Data Sensor</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #eee;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Laporan Data Sensor</h2>
    <p>Tanggal cetak: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Gas (ppm)</th>
                <th>Suhu (°C)</th>
                <th>Kelembaban (%)</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allData as $data)
                <tr>
                    <td>{{ $data->gas }}</td>
                    <td>{{ $data->suhu }}</td>
                    <td>{{ $data->kelembaban }}</td>
                    <td>{{ $data->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
