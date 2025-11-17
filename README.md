# Laporan Praktikum 8: PHP dan Database MySQL

1. Buatlah **_repository_** baru dengan nama **Lab8Web.**

2. Kerjakan semua latihan yang diberikan sesuai urutannya.

3. Screenshot setiap perubahannya.

4. Buatlah file **README.md** dan tuliskan penjelasan dari setiap langkah praktikum beserta screenshotnya.

5. **Commit** hasilnya pada **_repository_** masing-masing.

6. Kirim **URL _repository_** pada **_e-learning_** ecampus.

## Proses Pembuatan Program

**1. Membuat Database**

  Saya mulai dengan langkah paling dasar: membuat database bernama db_barang. Tabel data_barang saya buat seperti merapikan rak toko: kategori, nama barang, deskripsi, tanggal masuk, harga beli, harga jual, stok, hingga gambar barang. Setiap kolom saya susun seperti daftar belanjaan yang harus rapi biar tidak salah beli.

Struktur tabelnya adalah sebagai berikut:

- id_barang (INT, primary key)

- kategori (VARCHAR)

- nama (VARCHAR)

- deskripsi (TEXT)

- tanggal_masuk (DATE)

- gambar (VARCHAR)

- harga_beli (INT)

- harga_jual (INT)

- stok (INT)

Kolom tambahan seperti deskripsi dan tanggal masuk membuat tampilan datanya jadi tidak kaku.

<img width="580" height="482" alt="image" src="https://github.com/user-attachments/assets/7b3d3b7a-8068-4406-90b7-a0ab43b63344" />

**2. Menghubungkan PHP dan MySQL**

  Bagian ini seperti kenalan pertama kali: PHP harus menyapa MySQL lewat koneksi. File koneksi.php saya isi dengan kode sederhana untuk menyambungkan keduanya.

<img width="515" height="726" alt="image" src="https://github.com/user-attachments/assets/2561b2d1-9b40-48cc-9466-c3674a725442" />

**3. Membuat Halaman CRUD**

**a. READ: index.php**

Halaman ini menampilkan tabel data barang. Saya menata tampilannya dengan Bootstrap supaya terlihat rapi, seperti meja makan yang sudah dilap sebelum dipakai. Ada tombol Tambah, Edit, dan Hapus yang berjajar manis.

<img width="923" height="916" alt="image" src="https://github.com/user-attachments/assets/2642b032-8d5b-4e01-afae-d71c3031ef76" />

<img width="861" height="850" alt="image" src="https://github.com/user-attachments/assets/9713dc29-66f8-4545-90a5-140f4f9f7783" />

<img width="1564" height="776" alt="image" src="https://github.com/user-attachments/assets/e555f014-2ddd-4aa1-b1a1-5ede9f37c321" />

<img width="1337" height="762" alt="image" src="https://github.com/user-attachments/assets/d0c412cb-defa-42e1-b4c7-bbb69142a1b3" />

<img width="883" height="226" alt="image" src="https://github.com/user-attachments/assets/69e172f1-e64d-495e-8865-b4bc8bbd6fd4" />

**b. CREATE: tambah.php**

Halaman form ini adalah tempat barang baru lahir. Saya memasukkan semua input yang diperlukan, termasuk upload foto. Validasi file saya lakukan supaya tidak ada yang nekat upload foto KW atau file aneh.

<img width="784" height="949" alt="image" src="https://github.com/user-attachments/assets/5f95129b-165e-459e-8085-9188eca02a7e" />

<img width="1074" height="917" alt="image" src="https://github.com/user-attachments/assets/e2af2cd2-d9fd-4bdd-915b-052e5da68878" />

<img width="1240" height="837" alt="image" src="https://github.com/user-attachments/assets/e79eea49-f754-4d2f-94b7-0cc220178b97" />

<img width="938" height="916" alt="image" src="https://github.com/user-attachments/assets/c03b4c8a-061d-4236-9d0c-ad4574337c4d" />

<img width="1240" height="805" alt="image" src="https://github.com/user-attachments/assets/55cca6fd-17fc-4ec3-b6c7-5149927fadeb" />

<img width="1112" height="772" alt="image" src="https://github.com/user-attachments/assets/8333ace8-209c-4b25-be54-bba470e06376" />

**c. UPDATE: ubah.php**

Halaman edit tampil seperti reuni teman lama. Data barang muncul lebih dulu, lalu kita perbaiki bagian mana yang salah ketik atau berubah. Gambar pun bisa diganti atau dibiarkan tetap.

<img width="763" height="945" alt="image" src="https://github.com/user-attachments/assets/5f5a4b93-619d-439b-9b5c-fb6b1a210cf4" />

<img width="786" height="910" alt="image" src="https://github.com/user-attachments/assets/cce06210-cfd3-4008-a817-97b407147021" />

<img width="1272" height="795" alt="image" src="https://github.com/user-attachments/assets/e685d410-1f80-412d-9804-91720bb55496" />

**d. DELETE: hapus.php**

Menghapus data selalu punya sensasi dramatis. Tapi di CRUD, tombol hapus hanya menghilangkan baris di database dan file gambar. Tidak ada baper.
