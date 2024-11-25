<?php
include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama_kepala = $_POST['nama_kepala'];
    $nik_kepala = $_POST['nik_kepala'];
    $alamat = $_POST['alamat'];
    $rt = $_POST['rt'];
    $rw = $_POST['rw'];
    $kecamatan = $_POST['kecamatan'];
    $kelurahan = $_POST['kelurahan'];
    $nama_istri = $_POST['nama_istri'];
    $nik_istri = $_POST['nik_istri'];

    // Upload file
    $uploadDir = 'uploads/';
    $bukuNikahPath = $uploadDir . basename($_FILES['buku_nikah']['name']);
    move_uploaded_file($_FILES['buku_nikah']['tmp_name'], $bukuNikahPath);

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Masukkan data ke tabel kartu_keluarga dengan status 'pending'
        $stmt = $conn->prepare("
            INSERT INTO kartu_keluarga 
            (nama_kepala, nik_kepala, alamat, rt, rw, kecamatan, kelurahan, nama_istri, nik_istri, buku_nikah, status) 
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $status = 'pending'; // Set default status to 'pending'
        $stmt->bind_param(
            "sssssssssss", 
            $nama_kepala, 
            $nik_kepala, 
            $alamat, 
            $rt, 
            $rw, 
            $kecamatan, 
            $kelurahan, 
            $nama_istri, 
            $nik_istri, 
            $bukuNikahPath,
            $status
        );
        $stmt->execute();

        // Ambil ID dari kartu_keluarga yang baru dimasukkan
        $kartuKeluargaId = $conn->insert_id;

        // Masukkan data akte_anak jika ada
        if (isset($_FILES['akte_anak']) && !empty($_FILES['akte_anak']['name'][0])) {
            $stmtAkte = $conn->prepare("
                INSERT INTO akte_anak (kartu_keluarga_id, file_akte) 
                VALUES (?, ?)
            ");

            foreach ($_FILES['akte_anak']['name'] as $index => $fileName) {
                $filePath = $uploadDir . basename($fileName);
                move_uploaded_file($_FILES['akte_anak']['tmp_name'][$index], $filePath);
                $stmtAkte->bind_param("is", $kartuKeluargaId, $filePath);
                $stmtAkte->execute();
            }
        }

        // Commit transaksi
        $conn->commit();
        echo "Data berhasil disimpan.";

    } catch (Exception $e) {
        $conn->rollback();
        echo "Gagal menyimpan data: " . $e->getMessage();
    }
}
?>
