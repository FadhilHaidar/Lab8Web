<?php
require 'koneksi.php';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Data Barang — CRUD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .thumb { width:80px; height:80px; object-fit:cover; border-radius:6px; }
    .table-wrap { overflow-x:auto; }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Barang</h3>
    <a href="tambah.php" class="btn btn-primary">+ Tambah Barang</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-wrap">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-secondary">
            <tr>
              <th>#</th>
              <th>Gambar</th>
              <th>Nama</th>
              <th>Kategori</th>
              <th>Deskripsi</th>
              <th>Tgl Masuk</th>
              <th>Harga Beli</th>
              <th>Harga Jual</th>
              <th>Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = "SELECT * FROM data_barang ORDER BY id_barang DESC";
            $res = mysqli_query($koneksi, $query);
            if (!$res) {
                echo '<tr><td colspan="10">Terjadi kesalahan: '.mysqli_error($koneksi).'</td></tr>';
            } else {
                $no = 1;
                while ($row = mysqli_fetch_assoc($res)) {
                    $id = (int)$row['id_barang'];
                    $nama = htmlspecialchars($row['nama']);
                    $kategori = htmlspecialchars($row['kategori']);
                    $deskripsi = htmlspecialchars($row['deskripsi']);
                    $tgl = $row['tanggal_masuk'];
                    $gambar = $row['gambar'] ? 'gambar/' . $row['gambar'] : '';
                    $hb = number_format((int)$row['harga_beli']);
                    $hj = number_format((int)$row['harga_jual']);
                    $stok = (int)$row['stok'];
                    echo "<tr>
                      <td>{$no}</td>
                      <td>";
                    if ($gambar && file_exists($gambar)) {
                        echo "<img src=\"{$gambar}\" alt=\"{$nama}\" class=\"thumb\">";
                        echo "<pre>";
                        print_r($gambar);
                        echo "</pre>";
                    } else {
                        echo "<div style=\"width:80px;height:80px;display:flex;align-items:center;justify-content:center;background:#f1f1f1;border-radius:6px;color:#777\">No Img</div>";
                    }
                    echo "</td>
                      <td>{$nama}</td>
                      <td>{$kategori}</td>
                      <td style=\"max-width:240px;\">".nl2br($deskripsi)."</td>
                      <td>{$tgl}</td>
                      <td>Rp {$hb}</td>
                      <td>Rp {$hj}</td>
                      <td>{$stok}</td>
                      <td>
                        <a href=\"ubah.php?id={$id}\" class=\"btn btn-sm btn-warning mb-1\">Edit</a>
                        <a href=\"hapus.php?id={$id}\" class=\"btn btn-sm btn-danger\" onclick=\"return confirm('Yakin ingin menghapus barang ini?')\">Hapus</a>
                      </td>
                    </tr>";
                    $no++;
                }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <footer class="mt-3 text-muted">
    <small>CRUD sederhana - PHP + MySQL • Upload gambar maksimal 1MB • Format: jpg, jpeg, png</small>
  </footer>
</div>
</body>
</html>
