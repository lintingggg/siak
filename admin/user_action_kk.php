<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/connection.php'; // Koneksi database

// Periksa apakah admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
        alert('Akses ditolak! Anda harus login sebagai admin.');
        window.location.href = '../login.php';
    </script>";
    exit();
}

// Periksa apakah data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging: Log data POST untuk memastikan datanya benar
    error_log("POST Data: " . print_r($_POST, true));

    // Ambil data dari form dengan validasi
    $kartu_keluarga_id = filter_input(INPUT_POST, 'kartu_keluarga_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    // Validasi data yang diterima
    if (!$kartu_keluarga_id || !$action) {
        echo "<script>
            alert('Data tidak valid.');
            window.location.href = 'dashboard.php';
        </script>";
        exit();
    }

    // Tindakan delete
    if ($action === 'delete') {
        $stmtDelete = $conn->prepare("DELETE FROM kartu_keluarga WHERE id = ?");
        $stmtDelete->bind_param("i", $kartu_keluarga_id);

        if ($stmtDelete->execute()) {
            echo "<script>
                alert('Data kartu keluarga berhasil dihapus.');
                window.location.href = 'dashboard.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menghapus data.');
                window.location.href = 'dashboard.php';
            </script>";
        }
        $stmtDelete->close();
        $conn->close();
        exit();
    }

    // Tindakan lainnya
    $new_status = match ($action) {
        'approve' => 'approved',
        'reject' => 'rejected',
        'selesai' => 'selesai',
        default => null,
    };

    if (!$new_status) {
        echo "<script>
            alert('Tindakan tidak valid.');
            window.location.href = 'dashboard.php';
        </script>";
        exit();
    }

    // Update status di database
    $stmt = $conn->prepare("UPDATE kartu_keluarga SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $kartu_keluarga_id);

    if ($stmt->execute()) {
        echo "<script>
            alert('Status berhasil diupdate menjadi " . strtoupper($new_status) . "');
            window.location.href = 'dashboard.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal mengupdate status.');
            window.location.href = 'dashboard.php';
        </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Jika akses tidak valid, kembalikan ke halaman utama
    echo "<script>
        alert('Akses tidak valid.');
        window.location.href = 'dashboard.php';
    </script>";
    exit();
}
