<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        @page { size: A4; margin: 1.5cm; }
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 18px; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        th { background: #f2f2f2; }
        .text-right { text-align: right; }
        .center { text-align: center; }
        h3 { margin-top: 25px; }
    </style>
</head>
<body>

<div class="center">
    <h2>LAPORAN KEUANGAN SPOT PEMANCINGAN</h2>
    <p>
        Periode:
        {{ request('tanggal_mulai', '-') }}
        s/d
        {{ request('tanggal_selesai', '-') }}
    </p>
    <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<hr>

<h3>Ringkasan Umum</h3>
<table>
    <tr>
        <th>Total Transaksi</th>
        <td>{{ $transaksis->count() }} transaksi</td>
    </tr>
    <tr>
        <th>Total Pendapatan</th>
        <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th>Total Ikan Kecil</th>
        <td>{{ $totalIkanKecil }} ekor</td>
    </tr>
    <tr>
        <th>Total Berat Ikan Babon</th>
        <td>{{ number_format($totalBeratBaboon, 2) }} Kg</td>
    </tr>
</table>

<h3>Pendapatan per Spot</h3>
<table>
    <thead>
        <tr>
            <th>Spot</th>
            <th>Transaksi</th>
            <th>Total</th>
            <th>Kontribusi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataSpot as $spot)
        <tr>
            <td>{{ $spot['nama_spot'] }}</td>
            <td class="center">{{ $spot['jumlah_transaksi'] }}</td>
            <td class="text-right">Rp {{ number_format($spot['total_pendapatan'], 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($spot['persentase'], 2) }}%</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>Metode Pembayaran</h3>
<table>
    <thead>
        <tr>
            <th>Metode</th>
            <th>Jumlah</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dataPembayaran as $row)
        <tr>
            <td>{{ $row['nama_metode'] ?? 'Tidak Diketahui' }}</td>
            <td class="center">{{ $row['jumlah'] }}</td>
            <td class="text-right">
                Rp {{ number_format($row['total'], 0, ',', '.') }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>

<h3>Detail Transaksi</h3>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Spot</th>
            <th>Sesi</th>
            <th>Durasi</th>
            <th>Hasil</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksis as $t)
        <tr>
            <td>{{ $t->waktu_mulai->format('d/m/Y') }}</td>
            <td>{{ $t->nama_pelanggan }}</td>
            <td>{{ $t->spot->nama_spot ?? '-' }}</td>
            <td>{{ strtoupper($t->tipe_sesi) }}</td>
            <td>{{ $t->waktu_mulai->diffInHours($t->waktu_selesai) }} jam</td>
            <td>{{ $t->jumlah_ikan_kecil ?? 0 }} / {{ number_format($t->berat_ikan_babon ?? 0, 2) }} Kg</td>
            <td class="text-right">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" class="text-right">TOTAL</th>
            <th class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

</body>
</html>
