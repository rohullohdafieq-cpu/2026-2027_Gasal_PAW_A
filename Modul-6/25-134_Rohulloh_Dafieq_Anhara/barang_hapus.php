<?php
include 'koneksi.php';

// Pastikan ada parameter ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id_barang = mysqli_real_escape_string($koneksi, $_GET['id']);

    // 1. Cek apakah barang sudah digunakan pada tabel transaksi_detail
    $query_cek = "SELECT COUNT(*) AS total FROM transaksi_detail WHERE barang_id = '$id_barang'";
    $result_cek = mysqli_query($koneksi, $query_cek);
    $data_cek = mysqli_fetch_assoc($result_cek);

    if ($data_cek['total'] > 0) {
        // Jika data barang masih digunakan pada transaksi
        echo "<script>
                alert('Gagal menghapus! Data barang ini masih digunakan pada transaksi.');
                window.location = 'barang_index.php';
              </script>";
    } else {
        // 2. Jika barang belum pernah digunakan, proses hapus
        $query_hapus = "DELETE FROM barang WHERE id = '$id_barang'";
        
        if (mysqli_query($koneksi, $query_hapus)) {
            echo "<script>
                    alert('Data barang berhasil dihapus.');
                    window.location = 'barang_index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus barang: " . mysqli_error($koneksi) . "');
                    window.location = 'barang_index.php';
                  </script>";
        }
    }
} else {
    header("Location: barang_index.php");
}
?>