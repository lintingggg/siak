<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "siak_db");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mendapatkan data registrasi
$result = $conn->query("SELECT * FROM registrasi_akun");

// Tampilkan data
if ($result->num_rows > 0) {
    echo '<h5>Permintaan Registrasi Akun</h5>';
    echo '<table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Tanggal Registrasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>';
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>$no</td>
                <td>{$row['nama']}</td>
                <td>{$row['email']}</td>
                <td>{$row['tanggal_registrasi']}</td>
                <td>
                    <button class='btn btn-success btn-sm'>Terima</button>
                    <button class='btn btn-danger btn-sm'>Tolak</button>
                </td>
            </tr>";
        $no++;
    }
    echo '</tbody></table>';
} else {
    echo '<p class="text-center">Belum ada permintaan registrasi.</p>';
}
?>
