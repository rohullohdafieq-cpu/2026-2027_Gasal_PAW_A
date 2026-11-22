<?php 
include 'cek_session.php'; 
include 'koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM user");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Data User</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Username</th>
                                <th>Level / Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($data = mysqli_fetch_array($query)) { 
                                // Menyesuaikan tampilan level/role dengan index.php Anda
                                $nama = $data['nama'] ?? $data['nama_user'] ?? '-';
                                $role = isset($data['level']) 
                                        ? ($data['level'] == 1 ? 'Owner' : 'Kasir') 
                                        : ($data['role'] ?? '-');
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($nama); ?></td>
                                <td><?= htmlspecialchars($data['username']); ?></td>
                                <td><?= htmlspecialchars($role); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>