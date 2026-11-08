<?php
include 'koneksi.php';

// Ambil data pelanggan untuk dropdown master transaksi
$query_pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan");

// Ambil data barang untuk dropdown detail transaksi
$query_barang = mysqli_query($koneksi, "SELECT * FROM barang");
$data_barang = [];
while ($row = mysqli_fetch_assoc($query_barang)) {
    $data_barang[] = $row;
}

// Proses simpan transaksi ke database
if (isset($_POST['simpan_transaksi'])) {
    $waktu_transaksi = $_POST['waktu_transaksi'];
    $pelanggan_id    = $_POST['pelanggan_id'];
    $keterangan      = $_POST['keterangan'];
    $barang_ids      = $_POST['barang_id'] ?? [];
    $hargas          = $_POST['harga'] ?? [];
    $qtys            = $_POST['qty'] ?? [];

    // Hitung total harga transaksi
    $total_transaksi = 0;
    for ($i = 0; $i < count($barang_ids); $i++) {
        $total_transaksi += $hargas[$i] * $qtys[$i];
    }

    // 1. Insert ke tabel master (transaksi)
    $query_master = "INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) 
                     VALUES ('$waktu_transaksi', '$keterangan', '$total_transaksi', '$pelanggan_id')";
    
    if (mysqli_query($koneksi, $query_master)) {
        $transaksi_id = mysqli_insert_id($koneksi); // Dapatkan ID transaksi yang baru dibuat

        // 2. Insert ke tabel detail (transaksi_detail)
        for ($i = 0; $i < count($barang_ids); $i++) {
            $b_id  = $barang_ids[$i];
            $hrg   = $hargas[$i];
            $qty   = $qtys[$i];

            $query_detail = "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) 
                             VALUES ('$transaksi_id', '$b_id', '$hrg', '$qty')";
            mysqli_query($koneksi, $query_detail);
        }

        echo "<script>alert('Transaksi berhasil disimpan!'); window.location='transaksi_tambah.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan transaksi: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Transaksi Master-Detail</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .card { border: 1px solid #ccc; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn { padding: 8px 12px; cursor: pointer; border: none; border-radius: 4px; }
        .btn-add { background-color: #28a745; color: white; }
        .btn-remove { background-color: #dc3545; color: white; }
        .btn-submit { background-color: #007bff; color: white; font-size: 16px; margin-top: 15px; }
    </style>
</head>
<body>

<h2>Form Transaksi (Master-Detail)</h2>

<form method="POST" action="">
    <!-- MASTER TRANSAKSI -->
    <div class="card">
        <h3>Data Master Transaksi</h3>
        <label>Tanggal Transaksi:</label><br>
        <input type="date" name="waktu_transaksi" required value="<?= date('Y-m-d') ?>"><br><br>

        <label>Pelanggan:</label><br>
        <select name="pelanggan_id" required>
            <option value="">-- Pilih Pelanggan --</option>
            <?php while ($p = mysqli_fetch_assoc($query_pelanggan)) : ?>
                <option value="<?= $p['id'] ?>"><?= $p['nama'] ?> (<?= $p['id'] ?>)</option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Keterangan:</label><br>
        <textarea name="keterangan" rows="2" style="width: 100%;"></textarea>
    </div>

    <!-- DETAIL TRANSAKSI -->
    <div class="card">
        <h3>Detail Barang Transaksi</h3>
        <button type="button" class="btn btn-add" id="add-row">+ Tambah Barang</button>
        
        <table id="table-detail">
            <thead>
                <tr>
                    <th>Pilih Barang</th>
                    <th>Harga (Rp)</th>
                    <th>Jumlah (Qty)</th>
                    <th>Subtotal (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Row detail barang akan ditambahkan secara dinamis lewat JavaScript -->
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right;">Total Keseluruhan:</th>
                    <th id="grand-total">0</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <button type="submit" name="simpan_transaksi" class="btn btn-submit">Simpan Transaksi</button>
</form>

<!-- Include JavaScript/jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Menyiapkan data barang dari PHP ke JS
const listBarang = <?= json_encode($data_barang); ?>;

$(document).ready(function() {
    
    // Fungsi untuk menambah baris barang baru
    $('#add-row').click(function() {
        let options = '<option value="">-- Pilih Barang --</option>';
        listBarang.forEach(function(barang) {
            options += `<option value="${barang.id}" data-harga="${barang.harga}">${barang.nama_barang} (Stok: ${barang.stok})</option>`;
        });

        let rowHtml = `
            <tr class="row-barang">
                <td>
                    <select name="barang_id[]" class="select-barang" required>
                        ${options}
                    </select>
                </td>
                <td><input type="number" name="harga[]" class="harga" readonly value="0"></td>
                <td><input type="number" name="qty[]" class="qty" min="1" value="1" required></td>
                <td><span class="subtotal">0</span></td>
                <td><button type="button" class="btn btn-remove remove-row">Hapus</button></td>
            </tr>
        `;
        
        $('#table-detail tbody').append(rowHtml);
        updateDropdownOptions(); // Perbarui opsi dropdown yang di-disable/enable
    });

    // Event ketika dropdown barang dipilih/diubah
    $(document).on('change', '.select-barang', function() {
        let selectedOption = $(this).find('option:selected');
        let harga = selectedOption.data('harga') || 0;
        let row = $(this).closest('tr');
        
        row.find('.harga').val(harga);
        calculateRowSubtotal(row);
        updateDropdownOptions(); // Disable opsi yang sudah dipilih di baris lain
    });

    // Event ketika Qty diubah
    $(document).on('input', '.qty', function() {
        let row = $(this).closest('tr');
        calculateRowSubtotal(row);
    });

    // Event untuk menghapus baris barang
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        updateDropdownOptions();
        calculateGrandTotal();
    });

    // Fungsi menghitung subtotal per baris
    function calculateRowSubtotal(row) {
        let harga = parseFloat(row.find('.harga').val()) || 0;
        let qty = parseInt(row.find('.qty').val()) || 0;
        let subtotal = harga * qty;
        row.find('.subtotal').text(subtotal.toLocaleString('id-ID'));
        calculateGrandTotal();
    }

    // Fungsi menghitung total seluruh transaksi
    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.row-barang').each(function() {
            let harga = parseFloat($(this).find('.harga').val()) || 0;
            let qty = parseInt($(this).find('.qty').val()) || 0;
            grandTotal += (harga * qty);
        });
        $('#grand-total').text(grandTotal.toLocaleString('id-ID'));
    }

    // FUNGSI UTAMA: Menonaktifkan (disabled) barang yang sudah dipilih di baris lain
    function updateDropdownOptions() {
        let selectedValues = [];

        // Kumpulkan semua ID barang yang sudah dipilih pada setiap select
        $('.select-barang').each(function() {
            let val = $(this).val();
            if (val) {
                selectedValues.push(val);
            }
        });

        // Iterasi semua select untuk mengaktifkan/menonaktifkan opsi
        $('.select-barang').each(function() {
            let currentSelectVal = $(this).val();

            $(this).find('option').each(function() {
                let optionVal = $(this).val();
                
                if (optionVal === "") return; // Lewati placeholder

                // Disable opsi jika nilainya sudah dipilih di select LAIN
                if (selectedValues.includes(optionVal) && optionVal !== currentSelectVal) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });
        });
    }

    // Tambah 1 baris pertama secara otomatis saat halaman pertama dimuat
    $('#add-row').click();
});
</script>

</body>
</html>