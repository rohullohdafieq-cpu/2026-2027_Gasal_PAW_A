<?php
include 'koneksi.php';

// Default tanggal: awal bulan ini sampai hari ini jika filter belum ditekan
$tanggal_mulai   = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : date('Y-m-01');
$tanggal_selesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : date('Y-m-d');

// 1. Query untuk Grafik & Tabel Rekapitulasi Harian
$query_harian = "SELECT 
                    waktu_transaksi, 
                    SUM(total) AS total_pendapatan 
                 FROM transaksi 
                 WHERE waktu_transaksi BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'
                 GROUP BY waktu_transaksi 
                 ORDER BY waktu_transaksi ASC";
$result_harian = mysqli_query($koneksi, $query_harian);

$data_rekap = [];
$labels_tanggal = [];
$data_pendapatan = [];

while ($row = mysqli_fetch_assoc($result_harian)) {
    $data_rekap[] = $row;
    // Format tanggal untuk sumbu X grafik (contoh: 15 Nov 2026)
    $labels_tanggal[] = date('d M Y', strtotime($row['waktu_transaksi']));
    $data_pendapatan[] = (int)$row['total_pendapatan'];
}

// 2. Query untuk Card Ringkasan Kumulatif (Pelanggan Unik & Total Pendapatan Keseluruhan)
$query_ringkasan = "SELECT 
                        COUNT(DISTINCT pelanggan_id) AS jumlah_pelanggan, 
                        SUM(total) AS total_keseluruhan 
                    FROM transaksi 
                    WHERE waktu_transaksi BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'";
$result_ringkasan = mysqli_query($koneksi, $query_ringkasan);
$ringkasan = mysqli_fetch_assoc($result_ringkasan);

$jumlah_pelanggan = $ringkasan['jumlah_pelanggan'] ?? 0;
$total_keseluruhan = $ringkasan['total_keseluruhan'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <!-- CDN Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .periode-text {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
        /* Styling Form Filter & Tombol */
        .filter-box {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: flex-end;
            background: #f1f5f9;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .form-group input {
            padding: 7px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
        }
        .btn-primary { background-color: #2563eb; color: white; }
        .btn-print { background-color: #4b5563; color: white; }
        .btn-excel { background-color: #16a34a; color: white; }

        /* Styling Grafik */
        .chart-container {
            position: relative;
            height: 320px;
            width: 100%;
            margin-bottom: 30px;
        }

        /* Styling Tabel Rekapitulasi */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table, th, td {
            border: 1px solid #cbd5e1;
        }
        th {
            background-color: #e2e8f0;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 10px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Styling Card Ringkasan */
        .summary-cards {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }
        .card {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 5px solid #2563eb;
            padding: 15px 20px;
            border-radius: 6px;
        }
        .card.revenue {
            border-left-color: #16a34a;
        }
        .card h4 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #64748b;
        }
        .card .value {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
        }

        /* Aturan CSS saat dicetak (Print / Save as PDF) */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #fff;
                margin: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Laporan Rekapitulasi Penjualan</h2>
    <p class="periode-text">
        Periode: <strong><?= date('d/m/Y', strtotime($tanggal_mulai)) ?></strong> s/d <strong><?= date('d/m/Y', strtotime($tanggal_selesai)) ?></strong>
    </p>

    <!-- 1. Form Filter Rentang Tanggal & Tombol Aksi (Disembunyikan saat Print) -->
    <div class="filter-box no-print">
        <form method="GET" action="" style="display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;">
            <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= $tanggal_mulai ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?= $tanggal_selesai ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>

        <div style="margin-left: auto; display: flex; gap: 8px;">
            <!-- Tombol Cetak -->
            <button type="button" onclick="window.print()" class="btn btn-print">Cetak (Print)</button>
            
            <!-- Tombol Ekspor Excel -->
            <a href="export_excel.php?tanggal_mulai=<?= $tanggal_mulai ?>&tanggal_selesai=<?= $tanggal_selesai ?>" class="btn btn-excel">Ekspor Excel</a>
        </div>
    </div>

    <!-- 2. Visualisasi Grafik Batang (Chart.js) -->
    <div class="chart-container">
        <canvas id="grafikPenjualan"></canvas>
    </div>

    <!-- 3. Tabel Rekapitulasi Data Penjualan Harian -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 60px;">No</th>
                <th>Total Pendapatan</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($data_rekap) > 0): ?>
                <?php $no = 1; foreach ($data_rekap as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>Rp <?= number_format($row['total_pendapatan'], 0, ',', '.') ?></td>
                        <td><?= date('d-m-Y', strtotime($row['waktu_transaksi'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data transaksi pada rentang tanggal ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Card Ringkasan Informasi Total Kumulatif -->
    <div class="summary-cards">
        <div class="card">
            <h4>Jumlah Pelanggan Unik</h4>
            <div class="value"><?= number_format($jumlah_pelanggan, 0, ',', '.') ?> Orang</div>
        </div>
        <div class="card revenue">
            <h4>Total Pendapatan Keseluruhan</h4>
            <div class="value">Rp <?= number_format($total_keseluruhan, 0, ',', '.') ?></div>
        </div>
    </div>
</div>

<!-- Konfigurasi Chart.js -->
<script>
    const ctx = document.getElementById('grafikPenjualan').getContext('2d');
    const grafikPenjualan = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels_tanggal) ?>, // Sumbu X: Tanggal Transaksi
            datasets: [{
                label: 'Total Pendapatan (Rp)',
                data: <?= json_encode($data_pendapatan) ?>, // Sumbu Y: Nominal Pendapatan
                backgroundColor: 'rgba(37, 99, 235, 0.7)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Total: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>