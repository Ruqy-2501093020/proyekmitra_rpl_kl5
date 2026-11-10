<?php
session_start();
include "koneksi.php";


if (!isset($_SESSION['username'])) {
    header("Location:login.php");
    exit();
}


$cari = "";
if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
}

if ($cari != "") {
    $sql = mysqli_query($koneksi, "SELECT * FROM kontrakan
                                   WHERE nama_kontrakan LIKE '%$cari%'
                                   OR lokasi LIKE '%$cari%'
                                   OR fasilitas LIKE '%$cari%'
                                   ORDER BY id_kontrakan ASC");
} else {
    $sql = mysqli_query($koneksi, "SELECT * FROM kontrakan ORDER BY id_kontrakan ASC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kontrakan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { color: #444; }
        .topbar { height: 68px; border-bottom: 1px solid #444; }
        .sidebar { width: 135px; min-height: calc(100vh - 68px); border-right: 1px solid #444; }
        .menu-btn { width: 88px; height: 44px; border: 1px solid #444; color: #444; font-size: 1.3rem;
                    display: flex; align-items: center; padding-left: 8px; text-decoration: none; background: #fff; }
        .menu-btn:hover { background: #eee; color: #222; }
        .menu-home { width: 80px; height: 72px; border: 1px solid #444; border-radius: 50%; color: #444;
                     font-size: 2rem; display: flex; align-items: center; justify-content: center; text-decoration: none; }
        .bar-cari { border: 1px solid #444; padding: 12px 20px; }
        .bar-cari .form-control { border: 1px solid #444; border-radius: 0; background: #f3f3f3; }
        .btn-tambah { border: 1px solid #444; border-radius: 0; font-size: 1.4rem; line-height: 1; padding: 4px 28px; color: #444; background: #fff; }
        .btn-tambah:hover { background: #eee; }
        table.tabel { border: 1px solid #444; }
        table.tabel th, table.tabel td { border: 1px solid #444 !important; vertical-align: middle; }
        .foto { width: 160px; height: 94px; background: #ddd; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .foto img { width: 100%; height: 100%; object-fit: cover; }
        .aksi a { font-size: 1.5rem; color: #444; text-decoration: none; }
        .aksi a:hover { color: #000; }
    </style>
</head>
<body class="bg-white">

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center px-4">
    <a href="data_kontrakan.php" class="text-decoration-none" style="color:#444; font-size:1.8rem;"><i class="bi bi-house-fill"></i></a>

    <div class="dropdown">
        <a href="#" class="text-decoration-none" style="color:#444; font-size:2rem;" data-bs-toggle="dropdown" aria-label="Akun">
            <i class="bi bi-person-circle"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><span class="dropdown-item-text small">Masuk sebagai <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></span></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Keluar</a></li>
        </ul>
    </div>
</div>

<div class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column align-items-center gap-3 pt-4">
        <a href="data_kontrakan.php" class="menu-home" title="Beranda"><i class="bi bi-house-fill"></i></a>
        <a href="#" class="menu-btn" title="Statistik"><i class="bi bi-bar-chart-fill"></i></a>
        <a href="#" class="menu-btn" title="Laporan"><i class="bi bi-file-earmark-text-fill"></i></a>
        <a href="#" class="menu-btn" title="Penyewa"><i class="bi bi-person-lines-fill"></i></a>
        <a href="#" class="menu-btn" title="Pengaturan"><i class="bi bi-gear-fill"></i></a>
    </div>

    <!-- Konten -->
    <div class="flex-grow-1 p-3">

        <!-- Pencarian dan tombol tambah -->
        <form method="GET" action="" class="bar-cari d-flex align-items-center gap-3 mb-3">
            <i class="bi bi-search" style="font-size:1.4rem;"></i>
            <input class="form-control w-auto" style="min-width:230px;" type="text" name="cari" placeholder="Cari kontrakan" value="<?php echo htmlspecialchars($cari); ?>">
            <a href="tambah_kontrakan.php" class="btn btn-tambah ms-auto" title="Tambah kontrakan"><i class="bi bi-plus-lg"></i></a>
        </form>

        <!-- Tabel -->
        <div class="table-responsive">
            <table class="table tabel mb-0">
                <thead>
                    <tr>
                        <th style="width:200px;">Foto</th>
                        <th>Data kontrakan</th>
                        <th style="width:150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (mysqli_num_rows($sql) > 0) {
                    while ($row = mysqli_fetch_assoc($sql)) {
                ?>
                    <tr>
                        <td>
                            <div class="foto">
                                <?php if (!empty($row['foto'])) { ?>
                                    <img src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto <?php echo htmlspecialchars($row['nama_kontrakan']); ?>">
                                <?php } else { ?>
                                    <i class="bi bi-image text-secondary opacity-50" style="font-size:2.5rem;"></i>
                                <?php } ?>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold"><?php echo htmlspecialchars($row['nama_kontrakan']); ?></div>
                            <div class="small"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($row['lokasi']); ?></div>
                            <div class="small">Rp <?php echo number_format($row['harga_sewa'], 0, ',', '.'); ?></div>
                            <div class="small text-secondary"><?php echo htmlspecialchars($row['fasilitas']); ?></div>
                            <?php if ($row['status'] == "Tersedia") { ?>
                                <span class="badge text-bg-success mt-1"><?php echo $row['status']; ?></span>
                            <?php } else { ?>
                                <span class="badge text-bg-secondary mt-1"><?php echo htmlspecialchars($row['status']); ?></span>
                            <?php } ?>
                        </td>
                        <td class="aksi text-center">
                            <a href="edit_kontrakan.php?id=<?php echo $row['id_kontrakan']; ?>" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <a href="hapus_kontrakan.php?id=<?php echo $row['id_kontrakan']; ?>" class="ms-3" title="Hapus"
                               onclick="return confirm('Hapus kontrakan ini?')"><i class="bi bi-trash-fill"></i></a>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="3" class="text-center py-4">Data kontrakan tidak ditemukan.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>