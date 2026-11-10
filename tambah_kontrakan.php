
<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location:login.php");
    exit();
}

$pesan = "";

if (isset($_POST['submit'])) {
    $nama_kontrakan = trim($_POST['nama_kontrakan']);
    $lokasi = trim($_POST['lokasi']);
    $harga_sewa = (float) $_POST['harga_sewa'];
    $fasilitas = trim($_POST['fasilitas']);
    $status = trim($_POST['status']);
    $foto = null;

    // Upload foto kontrakan
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $pesan = "Foto gagal diunggah.";
        } else {
            $ekstensi = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $izin = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ekstensi, $izin, true)) {
                $pesan = "Format foto harus JPG, JPEG, PNG, atau WEBP.";
            } else {
                if (!is_dir("uploads")) {
                    mkdir("uploads", 0775, true);
                }

                $nama_file = "kontrakan_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ekstensi;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $nama_file)) {
                    $foto = $nama_file;
                } else {
                    $pesan = "Foto gagal disimpan.";
                }
            }
        }
    }

    // Simpan data ke database
    if ($pesan === "") {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO kontrakan
            (nama_kontrakan, lokasi, harga_sewa, fasilitas, status, foto)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssdsss",
            $nama_kontrakan,
            $lokasi,
            $harga_sewa,
            $fasilitas,
            $status,
            $foto
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location:data_kontrakan.php?pesan=berhasil_tambah");
            exit();
        } else {
            $pesan = "Data gagal ditambahkan: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Data Kontrakan</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    color: #333;
}

.container {
    width: 90%;
    max-width: 850px;
    margin: 45px auto;
}

.header {
    margin-bottom: 20px;
}

.header h2 {
    margin-bottom: 8px;
    color: #263445;
}

.header p {
    color: #777;
    margin: 0;
}

.card {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    text-align: left;
    background: #f1f4f8;
    padding: 14px;
    width: 30%;
    border: 1px solid #e2e6ea;
}

td {
    padding: 12px;
    border: 1px solid #e2e6ea;
}

input[type="text"],
input[type="number"],
input[type="file"],
textarea,
select {
    width: 100%;
    padding: 11px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 14px;
}

textarea {
    resize: vertical;
}

input:focus,
textarea:focus,
select:focus {
    outline: none;
    border-color: #4389d6;
}

.tombol {
    margin-top: 20px;
    display: flex;
    gap: 10px;
}

.btn {
    padding: 11px 20px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
}

.btn-simpan {
    background: #2878c7;
    color: white;
}

.btn-kembali {
    background: #6c757d;
    color: white;
}

.btn:hover {
    opacity: 0.85;
}

.alert {
    background: #f8d7da;
    color: #842029;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
}

@media(max-width: 600px) {
    .container {
        width: 95%;
    }

    th, td {
        display: block;
        width: 100%;
    }

    th {
        border-bottom: none;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2>Tambah Data Kontrakan</h2>
        <p>Masukkan informasi kontrakan baru melalui formulir berikut.</p>
    </div>

    <div class="card">

        <?php if ($pesan !== ""): ?>
            <div class="alert">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <table>
                <tr>
                    <th>Nama Kontrakan</th>
                    <td>
                        <input type="text" name="nama_kontrakan"
                        placeholder="Masukkan nama kontrakan"
                        value="<?= htmlspecialchars($_POST['nama_kontrakan'] ?? '') ?>"
                        required>
                    </td>
                </tr>

                <tr>
                    <th>Lokasi</th>
                    <td>
                        <textarea name="lokasi" rows="3"
                        placeholder="Masukkan alamat kontrakan"
                        required><?= htmlspecialchars($_POST['lokasi'] ?? '') ?></textarea>
                    </td>
                </tr>

                <tr>
                    <th>Harga Sewa</th>
                    <td>
                        <input type="number" name="harga_sewa"
                        min="0" step="1"
                        placeholder="Contoh: 1200000"
                        value="<?= htmlspecialchars($_POST['harga_sewa'] ?? '') ?>"
                        required>
                    </td>
                </tr>

                <tr>
                    <th>Fasilitas</th>
                    <td>
                        <textarea name="fasilitas" rows="3"
                        placeholder="Contoh: Kamar mandi dalam, dapur, parkir motor"><?= htmlspecialchars($_POST['fasilitas'] ?? '') ?></textarea>
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <select name="status" required>
                            <option value="Tersedia"
                            <?= (($_POST['status'] ?? 'Tersedia') === 'Tersedia') ? 'selected' : '' ?>>
                                Tersedia
                            </option>

                            <option value="Terisi"
                            <?= (($_POST['status'] ?? '') === 'Terisi') ? 'selected' : '' ?>>
                                Terisi
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>Foto Kontrakan</th>
                    <td>
                        <input type="file" name="foto"
                        accept=".jpg,.jpeg,.png,.webp">
                        <small>Format JPG, JPEG, PNG, atau WEBP.</small>
                    </td>
                </tr>
            </table>

            <div class="tombol">
                <button type="submit" name="submit" class="btn btn-simpan">
                    Simpan Data
                </button>

                <a href="data_kontrakan.php" class="btn btn-kembali">
                    Kembali
                </a>
            </div>

        </form>
    </div>
</div>

</body>
</html>
