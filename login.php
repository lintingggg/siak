<?php
session_start(); // Memulai sesi
include 'config/connection.php'; // Koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mencari pengguna berdasarkan email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password langsung (TANPA HASH)
        if ($password === $user['password']) {
            // Cek status akun
            if ($user['status'] === 'approved') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['nama_lengkap'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['foto_ktp'] = $user['foto_ktp'];
                $_SESSION['foto_diri_ktp'] = $user['foto_diri_ktp'];

                // Jika login berhasil, cek role (admin atau user)
                if ($_SESSION['role'] === 'admin') {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Berhasil',
                                text: 'Selamat datang, Admin " . $user['nama_lengkap'] . "!',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = 'admin/dashboard.php';
                            });
                        });
                    </script>";
                } else {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Berhasil',
                                text: 'Selamat datang, " . $user['nama_lengkap'] . "!',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = 'user/dashboard.html';
                            });
                        });
                    </script>";
                }
            } elseif ($user['status'] === 'pending') {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'info',
                            title: 'Akun Belum Disetujui',
                            text: 'Akun Anda sedang menunggu persetujuan admin.',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>";
            } elseif ($user['status'] === 'rejected') {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Akun Ditolak',
                            text: 'Akun Anda telah ditolak. Silakan hubungi admin.',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>";
            }
        } else {
            // Jika password salah
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal',
                        text: 'Password salah!',
                        confirmButtonText: 'Coba Lagi'
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                });
            </script>";
        }
    } else {
        // Jika email tidak ditemukan
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: 'Email tidak ditemukan!',
                    confirmButtonText: 'Coba Lagi'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            });
        </script>";
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Login</h3>
                </div>
                <div class="card-body">
                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me">
                            <label class="form-check-label" for="remember_me">Remember me</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p>Belum punya akun? <a href="register.php" class="text-decoration-none">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
