<?php
require 'koneksi.php';

$maxFileSize = 1 * 1024 * 1024; // 1 MB
$allowedTypes = ['image/jpeg','image/jpg','image/png'];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// ambil data lama
$q = mysqli_query($koneksi, "SELECT * FROM data_barang WHERE id_barang = $id LIMIT 1");
if (!$q || mysqli_num_rows($q) === 0) {
    header('Location: index.php');
    exit;
}
$data = mysqli_fetch_assoc($q);

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = trim(mysqli_real_escape_string($koneksi, $_POST['kategori'] ?? ''));
    $nama = trim(mysqli_real_escape_string($koneksi, $_POST['nama'] ?? ''));
    $deskripsi = trim(mysqli_real_escape_string($koneksi, $_POST['deskripsi'] ?? ''));
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? date('Y-m-d');
    $harga_beli = (int)($_POST['harga_beli'] ?? 0);
    $harga_jual = (int)($_POST['harga_jual'] ?? 0);
    $stok = (int)($_POST['stok'] ?? 0);

    if ($nama === '') $errors[] = 'Nama barang wajib diisi.';
    if ($kategori === '') $errors[] = 'Kategori wajib diisi.';

    $gambarName = $data['gambar']; // tetap pakai gambar lama kalau tidak diganti

    // jika upload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['gambar'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Terjadi kesalahan saat upload gambar.';
        } elseif ($file['size'] > $maxFileSize) {
            $errors[] = 'Ukuran gambar maksimal 1 MB.';
        } elseif (!in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {
            $errors[] = 'Format gambar tidak diizinkan. Gunakan jpg/jpeg/png.';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
            $dest = __DIR__ . '/gambar/' . $newName;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                // hapus gambar lama bila ada
                if ($gambarName && file_exists(__DIR__ . '/gambar/' . $gambarName)) {
                    @unlink(__DIR__ . '/gambar/' . $gambarName);
                }
                $gambarName = $newName;
            } else {
                $errors[] = 'Gagal memindahkan file gambar.';
            }
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE data_barang SET kategori=?, nama=?, deskripsi=?, tanggal_masuk=?, gambar=?, harga_beli=?, harga_jual=?, stok=? WHERE id_barang=?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssiiiii', $kategori, $nama, $deskripsi, $tanggal_masuk, $gambarName, $harga_beli, $harga_jual, $stok, $id);
        $exec = mysqli_stmt_execute($stmt);
        if ($exec) {
            $success = 'Data berhasil diupdate.';
            // reload data
            $q2 = mysqli_query($koneksi, "SELECT * FROM data_barang WHERE id_barang = $id LIMIT 1");
            $data = mysqli_fetch_assoc($q2);
        } else {
            $errors[] = 'Gagal update: ' . mysqli_error($koneksi);
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Barang</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>.thumb{width:120px;height:120px;object-fit:cover;border-radius:8px}</style>
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Edit Barang</h4>
    <a href="index.php" class="btn btn-secondary">← Kembali</a>
  </div>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul class="mb-0"><?php foreach ($errors as $e) echo '<li>' . htmlspecialchars($e) . '</li>'; ?></ul>
    </div>
  <?php endif; ?>
  <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Kategori</label>
            <input type="text" name="kategori" class="form-control" value="<?= htmlspecialchars($data['kategori']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Nama Barang</label>
            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>">
          </div>
          <div class="col-12">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" class="form-control" value="<?= htmlspecialchars($data['tanggal_masuk']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Harga Beli (Rp)</label>
            <input type="number" name="harga_beli" class="form-control" value="<?= htmlspecialchars($data['harga_beli']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Harga Jual (Rp)</label>
            <input type="number" name="harga_jual" class="form-control" value="<?= htmlspecialchars($data['harga_jual']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= htmlspecialchars($data['stok']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Gambar Saat Ini</label><br>
            <?php
            if ($data['gambar'] && file_exists(__DIR__ . '/gambar/' . $data['gambar'])) {
                echo '<img src="gambar/' . htmlspecialchars($data['gambar']) . '" class="thumb" alt="gambar">';
            } else {
                echo '<div style="width:120px;height:120px;display:flex;align-items:center;justify-content:center;background:#f1f1f1;border-radius:8px;color:#777">No Img</div>';
            }
            ?>
          </div>
          <div class="col-md-4">
            <label class="form-label">Ganti Gambar (opsional)</label>
            <input type="file" name="gambar" class="form-control">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti.</small>
          </div>
        </div>

        <div class="mt-3">
          <button class="btn btn-primary">Update</button>
          <a href="index.php" class="btn btn-outline-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
