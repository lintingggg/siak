<?php
include 'config/connection.php'; // Koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
    $nik = htmlspecialchars($_POST['nik']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password
    if ($password !== $confirm_password) {
        echo "<script>
            alert('Password dan konfirmasi password tidak cocok!');
            window.location.href = 'register.php';
        </script>";
        exit();
    }

    // Upload file
    $foto_ktp = $_FILES['foto_ktp'];
    $foto_diri = $_FILES['foto_diri'];

    $foto_ktp_path = "uploads/ktp_" . time() . "_" . basename($foto_ktp['name']);
    $foto_diri_path = "uploads/diri_" . time() . "_" . basename($foto_diri['name']);

    move_uploaded_file($foto_ktp['tmp_name'], $foto_ktp_path);
    move_uploaded_file($foto_diri['tmp_name'], $foto_diri_path);

    // Periksa apakah email atau NIK sudah terdaftar
    $checkQuery = $conn->prepare("SELECT * FROM users WHERE email = ? OR nik = ?");
    $checkQuery->bind_param("ss", $email, $nik);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Email atau NIK sudah terdaftar!');
            window.location.href = 'register.php';
        </script>";
    } else {
        // Simpan data ke database dengan status PENDING
        $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, nik, email, password, foto_ktp, foto_diri_ktp, status) 
                                 VALUES (?, ?, ?, ?, ?, ?, 'PENDING')");
        $stmt->bind_param("ssssss", $nama_lengkap, $nik, $email, $password, $foto_ktp_path, $foto_diri_path);

        if ($stmt->execute()) {
            echo "<script>
                alert('Registrasi berhasil! Harap tunggu persetujuan admin.');
                window.location.href = 'login.php';
            </script>";
        } else {
            echo "<script>
                alert('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
                window.location.href = 'register.php';
            </script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Register</h3>
                    </div>
                    <div class="card-body">
                        <form action="register.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" name="nik" class="form-control" maxlength="16" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="foto_ktp" class="form-label">Upload Foto KTP</label>
                                <input type="file" name="foto_ktp" class="form-control" accept="image/*" required>
                            </div>
                            <div class="mb-3">
                                <label for="foto_diri" class="form-label">Upload Foto Diri dengan KTP</label>
                                <input type="file" name="foto_diri" class="form-control" accept="image/*" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p>Sudah punya akun? <a href="login.php" class="text-decoration-none">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
