<?php
session_start();
include "koneksi.php";

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location:login.php");
    exit();
}

// Ambil ID kontrakan dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location:data_kontrakan.php?pesan=id_tidak_valid");
    exit();
}

// Ambil data foto sebelum menghapus data kontrakan
$stmt = mysqli_prepare($koneksi, "SELECT foto FROM kontrakan WHERE id_kontrakan = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location:data_kontrakan.php?pesan=data_tidak_ditemukan");
    exit();
}

// Hapus data kontrakan dari database
$hapus = mysqli_prepare($koneksi, "DELETE FROM kontrakan WHERE id_kontrakan = ?");
mysqli_stmt_bind_param($hapus, "i", $id);

if (mysqli_stmt_execute($hapus)) {

    // Hapus file foto jika ada di folder uploads
    if (!empty($data['foto'])) {
        $nama_foto = basename($data['foto']);
        $path_foto = "uploads/" . $nama_foto;

        if (is_file($path_foto)) {
            unlink($path_foto);
        }
    }

    header("Location:data_kontrakan.php?pesan=berhasil_hapus");
    exit();

} else {
    header("Location:data_kontrakan.php?pesan=gagal_hapus");
    exit();
}
?>
