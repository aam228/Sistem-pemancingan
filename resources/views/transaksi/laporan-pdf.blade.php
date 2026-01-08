<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; margin-top: 20px; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #eee; }
        h2 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Keuangan</h2>

    {{-- Ringkasan per Meja --}}
    <table style="margin-bottom: 30px;">
        <thead>
            <tr>
                <th>Nama Meja</th>
                <th>Total Durasi (jam)</th>
                <th>Total Pendapatan</th>
                <th>Persentase dari Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataMeja as $meja)
                <tr>
                    <td>{{ $meja['nama_meja'] }}</td>
                    <td style="text-align: right;">{{ rtrim(rtrim($meja['total_durasi'], '0'), '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($meja['total_pendapatan'], 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($meja['persentase'], 2) }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data meja.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Detail Transaksi --}}
    <table>
        <thead>
            <tr>
                <th colspan="8" style="text-align: right;">
                    <strong>Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
                </th>
            </tr>
            <tr>
                <th>Tanggal</th>
                <th>Nama Pelanggan</th>
                <th>Meja</th>
                <th>Durasi (jam)</th>
                <th>Ikan Kecil (ekor)</th>
                <th>Ikan Babon (kg)</th>
                <th>Total Harga</th>
                <th>Waktu Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $transaksi)
                <tr>
                    <td>{{ $transaksi->waktu_mulai->format('Y-m-d H:i') }}</td>
                    <td>{{ $transaksi->nama_pelanggan }}</td>
                    <td>{{ $transaksi->meja->nama_meja ?? 'Meja #' . $transaksi->meja_id }}</td>
                    <td>{{ $transaksi->durasi }}</td>
                    <td>{{ $transaksi->jumlah_ikan_kecil }}</td>
                    <td>{{ $transaksi->berat_ikan_babon }}</td>
                    <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $transaksi->waktu_selesai->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>