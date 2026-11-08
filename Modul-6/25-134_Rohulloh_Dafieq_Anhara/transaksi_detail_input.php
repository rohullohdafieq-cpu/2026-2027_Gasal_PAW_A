<?php
include 'koneksi.php';

// Ambil ID Transaksi dari URL (contoh: transaksi_detail_input.php?transaksi_id=1)
$transaksi_id = $_GET['transaksi_id'] ?? 1; 

// LOGIKA PEMROSESAN (Opsi A)
if (isset($_POST['simpan_detail'])) {
    $transaksi_id = mysqli_real_escape_string($koneksi, $_POST['transaksi_id']);
    $barang_id    = mysqli_real_escape_string($koneksi, $_POST['barang_id']);
    $harga        = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $qty          = mysqli_real_escape_string($koneksi, $_POST['qty']);

    // 1. Simpan detail transaksi baru
    $query_insert = "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) 
                    VALUES ('$transaksi_id', '$barang_id', '$harga', '$qty')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        
        // 2. Hitung ulang total perkalian (harga * qty) untuk transaksi ini
        $query_hitung = "SELECT SUM(harga * qty) AS total_baru 
                        FROM transaksi_detail 
                        WHERE transaksi_id = '$transaksi_id'";
        $result_hitung = mysqli_query($koneksi, $query_hitung);
        $row_hitung    = mysqli_fetch_assoc($result_hitung);
        $total_baru    = $row_hitung['total_baru'] ?? 0;

        // 3. Update kolom total di tabel transaksi
        $query_update = "UPDATE transaksi SET total = '$total_baru' WHERE id = '$transaksi_id'";
        mysqli_query($koneksi, $query_update);

        echo "<script>
                alert('Detail berhasil ditambah & Total Transaksi otomatis di-update menjadi Rp " . number_format($total_baru, 0, ',', '.') . "!');
                window.location = 'transaksi_detail_input.php?transaksi_id=$transaksi_id';
              </script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Data Pendukung untuk Form
$query_barang = mysqli_query($koneksi, "SELECT * FROM barang");
$query_header = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id = '$transaksi_id'");
$data_transaksi = mysqli_fetch_assoc($query_header);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Detail Transaksi (Nomor 4)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .box { border: 1px solid #ccc; padding: 15px; border-radius: 5px; width: 400px; }
    </style>
</head>
<body>

<h2>Form Input Detail Transaksi</h2>
<div class="box">
    <p><strong>ID Transaksi:</strong> <?= $data_transaksi['id']; ?></p>
    <p><strong>Total Transaksi Saat Ini:</strong> Rp <?= number_format($data_transaksi['total'], 0, ',', '.'); ?></p>
    <hr>
    
    <form method="POST" action="">
        <input type="hidden" name="transaksi_id" value="<?= $transaksi_id; ?>">
        
        <label>Pilih Barang:</label><br>
        <select name="barang_id" id="select-barang" required style="width: 100%; padding: 5px;">
            <option value="">-- Pilih Barang --</option>
            <?php while ($b = mysqli_fetch_assoc($query_barang)) : ?>
                <option value="<?= $b['id']; ?>" data-harga="<?= $b['harga']; ?>"><?= $b['nama_barang']; ?> (Rp <?= number_format($b['harga'], 0, ',', '.'); ?>)</option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Harga (Rp):</label><br>
        <input type="number" name="harga" id="harga" readonly required style="width: 95%; padding: 5px;"><br><br>

        <label>Jumlah (Qty):</label><br>
        <input type="number" name="qty" min="1" value="1" required style="width: 95%; padding: 5px;"><br><br>

        <button type="submit" name="simpan_detail" style="padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer;">
            + Simpan & Update Total Transaksi
        </button>
    </form>
</div>

<script>
    // Otomatis isi kolom harga saat barang dipilih
    document.getElementById('select-barang').addEventListener('change', function() {
        let harga = this.options[this.selectedIndex].getAttribute('data-harga') || 0;
        document.getElementById('harga').value = harga;
    });
</script>

</body>
</html>