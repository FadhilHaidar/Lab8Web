<?php
require 'koneksi.php';

// set konfigurasi upload
$maxFileSize = 1 * 1024 * 1024; // 1 MB
$allowedTypes = ['image/jpeg','image/jpg','image/png'];

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ambil input dengan trim
    $kategori = trim(mysqli_real_escape_string($koneksi, $_POST['kategori'] ?? ''));
    $nama = trim(mysqli_real_escape_string($koneksi, $_POST['nama'] ?? ''));
    $deskripsi = trim(mysqli_real_escape_string($koneksi, $_POST['deskripsi'] ?? ''));
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? null;
    $harga_beli = (int)($_POST['harga_beli'] ?? 0);
    $harga_jual = (int)($_POST['harga_jual'] ?? 0);
    $stok = (int)($_POST['stok'] ?? 0);

    // validasi sederhana
    if ($nama === '') $errors[] = 'Nama barang wajib diisi.';
    if ($kategori === '') $errors[] = 'Kategori wajib diisi.';
    if (!$tanggal_masuk) $tanggal_masuk = date('Y-m-d');

    // handle upload gambar
    $gambarName = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['gambar'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Terjadi kesalahan saat upload gambar.';
        } elseif ($file['size'] > $maxFileSize) {
            $errors[] = 'Ukuran gambar maksimal 1 MB.';
        } elseif (!in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {
            $errors[] = 'Format gambar tidak diizinkan. Gunakan jpg/jpeg/png.';
        } else {
            // generate nama aman
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $gambarName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
            $dest = __DIR__ . '/gambar/' . $gambarName;
            if (!move_uploaded_file($file['tmp_name'], $dest)) {
                $errors[] = 'Gagal memindahkan file gambar.';
                $gambarName = null;
            }
        }
    }

    if (empty($errors)) {
        // insert ke DB (prepared statement)
        $sql = "INSERT INTO data_barang (kategori, nama, deskripsi, tanggal_masuk, gambar, harga_beli, harga_jual, stok)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssiiii', $kategori, $nama, $deskripsi, $tanggal_masuk, $gambarName, $harga_beli, $harga_jual, $stok);
        $exec = mysqli_stmt_execute($stmt);
        if ($exec) {
            $success = 'Data barang berhasil ditambahkan.';
            // reset form values (optional)
            $_POST = [];
        } else {
            $errors[] = 'Gagal menyimpan ke database: ' . mysqli_error($koneksi);
            // hapus file jika sudah terupload
            if ($gambarName && file_exists(__DIR__ . '/gambar/' . $gambarName)) {
                unlink(__DIR__ . '/gambar/' . $gambarName);
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Barang</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Tambah Barang</h4>
    <a href="index.php" class="btn btn-secondary">← Kembali</a>
  </div>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Kategori</label>
            <input type="text" name="kategori" class="form-control" value="<?= htmlspecialchars($_POST['kategori'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Nama Barang</label>
            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
          </div>
          <div class="col-12">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" class="form-control" value="<?= htmlspecialchars($_POST['tanggal_masuk'] ?? date('Y-m-d')) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Harga Beli (Rp)</label>
            <input type="number" name="harga_beli" class="form-control" value="<?= htmlspecialchars($_POST['harga_beli'] ?? 0) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Harga Jual (Rp)</label>
            <input type="number" name="harga_jual" class="form-control" value="<?= htmlspecialchars($_POST['harga_jual'] ?? 0) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= htmlspecialchars($_POST['stok'] ?? 0) ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Gambar (jpg, png — max 1MB)</label>
            <input type="file" name="gambar" class="form-control">
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-success">Simpan</button>
          <a href="index.php" class="btn btn-outline-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
