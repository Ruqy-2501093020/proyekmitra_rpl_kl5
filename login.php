<?php
session_start();
include "koneksi.php";

$pesan = "";

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($koneksi,
        "SELECT * FROM admin WHERE username = ? AND password = SHA2(?, 256)"
    );

    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);

    $sql = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($sql) > 0) {

        $data = mysqli_fetch_assoc($sql);

        $_SESSION['username'] = $data['username'];
        $_SESSION['level'] = $data['level'];

        header("Location:data_kontrakan.php");
        exit();

    } else {

        $pesan = "Username atau Password salah";

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login QuadraTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .kotak { max-width: 820px; min-height: 540px; border: 1px solid #444; }
        .gambar { background: #ddd; background-size: cover; background-position: center; }
        /* Untuk memakai foto: .gambar { background-image: url('gambar/kontrakan.jpg'); } lalu hapus ikon di HTML */
        .form-input { max-width: 280px; }
        .form-input .form-control { border: 1px solid #444; border-radius: 0; height: 42px; }
        .form-input .form-control:focus { border-color: #8b1a1a; box-shadow: 0 0 0 .2rem rgba(139,26,26,.25); }
        .form-input .btn-mata { border: 1px solid #444; border-left: 0; border-radius: 0; }
        .ikon { font-size: 1.4rem; color: #444; width: 28px; }
        .btn-masuk { height: 60px; border-radius: 10px; font-weight: bold; background: #444; border-color: #444; }
        .btn-masuk:hover { background: #2e2e2e; border-color: #2e2e2e; }
    </style>
</head>
<body class="bg-white">

<div class="d-flex justify-content-center align-items-center vh-100">

    <div class="container kotak p-0 bg-white">
        <div class="row g-0 h-100" style="min-height:540px;">

            <!-- Kiri: gambar -->
            <div class="col-md-5 gambar d-none d-md-flex justify-content-center align-items-center">
                <img src="logo.png" alt="Logo Quadra Tech" style="width:80%; height:auto;">
            </div>

            <!-- Kanan: form login -->
            <div class="col-md-7 d-flex flex-column align-items-center py-5 px-3">

                <i class="bi bi-house-fill mb-5" style="font-size:5.5rem; color:#444; line-height:1;"></i>

                <?php if ($pesan != "") { ?>
                    <div class="alert alert-danger py-2 form-input w-100"><?php echo $pesan; ?></div>
                <?php } ?>

                <form method="POST" action="" class="form-input w-100">

                    <div class="d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-person-fill ikon"></i>
                        <input class="form-control" type="text" name="username" placeholder="Username" required>
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-lock-fill ikon"></i>
                        <div class="input-group">
                            <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
                            <button class="btn btn-light btn-mata" type="button" id="toggle" aria-label="Tampilkan password">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-4 small text-nowrap">
                        <input class="form-check-input mt-0" type="checkbox" name="ingat" id="ingat" style="width:24px;height:24px;">
                        <label class="form-check-label" for="ingat">Ingat saya</label>
                        <a href="#" class="ms-auto text-dark text-decoration-none">Lupa password?</a>
                    </div>

                    <input class="btn btn-dark btn-masuk w-100"
                           type="submit"
                           name="submit"
                           value="Masuk">
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('toggle').addEventListener('click', function () {
        var p = document.getElementById('password');
        p.type = (p.type === 'password') ? 'text' : 'password';
    });
</script>
</body>
</html>