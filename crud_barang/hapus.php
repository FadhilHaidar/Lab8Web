<?php
require 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// ambil nama file gambar
$q = mysqli_query($koneksi, "SELECT gambar FROM data_barang WHERE id_barang = $id LIMIT 1");
if ($q && mysqli_num_rows($q) > 0) {
    $row = mysqli_fetch_assoc($q);
    $gambar = $row['gambar'];
    // hapus record
    $del = mysqli_query($koneksi, "DELETE FROM data_barang WHERE id_barang = $id LIMIT 1");
    if ($del) {
        // hapus file gambar jika ada
        if ($gambar && file_exists(__DIR__ . '/gambar/' . $gambar)) {
            @unlink(__DIR__ . '/gambar/' . $gambar);
        }
        header('Location: index.php');
        exit;
    } else {
        die('Gagal menghapus data: ' . mysqli_error($koneksi));
    }
} else {
    header('Location: index.php');
    exit;
}
