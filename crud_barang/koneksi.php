<?php
// koneksi.php
// Konfigurasi koneksi database
$host = 'localhost';
$user = 'root';
$pass = ''; // sesuaikan jika ada password
$db   = 'db_barang';

// Create connection
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$koneksi) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($koneksi, 'utf8mb4');
?>
