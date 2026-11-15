<?php
include 'koneksi.php';

$tanggal_mulai   = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : date('Y-m-01');
$tanggal_selesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : date('Y-m-d');

// Manipulasi HTTP Response Header untuk download file Excel (.xls)
$nama_file = "Laporan_Penjualan_" . $tanggal_mulai . "_sd_" . $tanggal_selesai . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$nama_file\"");
header("Pragma: no-cache");
header("Expires: 0");

// Query Rekapitulasi Harian
$query_harian = "SELECT 
                    waktu_transaksi, 
                    SUM(total) AS total_pendapatan 
                 FROM transaksi 
                 WHERE waktu_transaksi BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'
                 GROUP BY waktu_transaksi 
                 ORDER BY waktu_transaksi ASC";
$result_harian = mysqli_query($koneksi, $query_harian);

// Query Ringkasan Kumulatif
$query_ringkasan = "SELECT 
                        COUNT(DISTINCT pelanggan_id) AS jumlah_pelanggan, 
                        SUM(total) AS total_keseluruhan 
                    FROM transaksi 
                    WHERE waktu_transaksi BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'";
$result_ringkasan = mysqli_query($koneksi, $query_ringkasan);
$ringkasan = mysqli_fetch_assoc($result_ringkasan);
?>

<h3>Laporan Rekapitulasi Penjualan</h3>
<p>Periode: <?= date('d/m/Y', strtotime($tanggal_mulai)) ?> s/d <?= date('d/m/Y', strtotime($tanggal_selesai)) ?></p>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr style="background-color: #e2e8f0;">
            <th>No</th>
            <th>Total Pendapatan</th>
            <th>Tanggal Transaksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while ($row = mysqli_fetch_assoc($result_harian)): 
        ?>
            <tr>
                <td align="center"><?= $no++ ?></td>
                <td><?= $row['total_pendapatan'] ?></td>
                <td><?= date('d-m-Y', strtotime($row['waktu_transaksi'])) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<br>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th align="left">Jumlah Pelanggan Unik</th>
        <td><?= $ringkasan['jumlah_pelanggan'] ?? 0 ?> Orang</td>
    </tr>
    <tr>
        <th align="left">Total Pendapatan Keseluruhan</th>
        <td>Rp <?= number_format($ringkasan['total_keseluruhan'] ?? 0, 0, ',', '.') ?></td>
    </tr>
</table>