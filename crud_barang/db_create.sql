-- db_create.sql
CREATE DATABASE IF NOT EXISTS db_barang CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_barang;

CREATE TABLE IF NOT EXISTS data_barang (
  id_barang INT AUTO_INCREMENT PRIMARY KEY,
  kategori VARCHAR(50) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  deskripsi TEXT,
  tanggal_masuk DATE,
  gambar VARCHAR(255),
  harga_beli INT DEFAULT 0,
  harga_jual INT DEFAULT 0,
  stok INT DEFAULT 0
);
