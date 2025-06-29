# 🚀 **To-Do List PHP: Neon Horizon** ✨

Selamat datang di proyek To-Do List PHP yang membawa Anda ke masa depan produktivitas! Dengan sentuhan estetika neon yang memukau dan desain responsif, ini bukan sekadar daftar tugas biasa, melainkan pengalaman visual yang modern dan interaktif. Siapkah Anda untuk mengorganisir hidup Anda dengan gaya?

## 🌟 **Fitur Unggulan**

* **Antarmuka Memukau:** Desain gelap yang elegan dengan aksen neon cerah dan gradien halus. ✨
* **Interaktif:** Efek visual menarik saat berinteraksi dengan tugas, seperti perubahan warna dan *glow* saat diklik. 🌈
* **Responsif Penuh:** Tampilan yang adaptif dan menawan di berbagai ukuran layar, dari desktop hingga *smartphone*. 📱
* **Manajemen Tugas Intuitif:** Tambahkan tugas baru dengan mudah dan tandai sebagai selesai dengan sekali klik. ✅
* **Persistensi Data:** Tugas-tugas Anda aman tersimpan di database MySQL. 💾

## ⚙️ **Persyaratan Sistem (Dasar Untuk Petualangan Ini)**

Untuk memulai perjalanan dengan Neon Horizon, pastikan Anda memiliki lingkungan server web yang siap:

* **PHP** (Versi 7.4+ direkomendasikan untuk performa optimal) ⚡
* **MySQL / MariaDB** (Sebagai gudang data Anda) 🗄️
* **Server Web** (Apache atau Nginx, sang jembatan antara kode dan browser) 🌐
* **Ekstensi PHP PDO MySQL** (Biasanya sudah aktif secara default di instalasi XAMPP) ✅

**Rekomendasi Terbaik:** Gunakan **XAMPP**! Ia adalah paket lengkap yang akan memudahkan Anda menginstal semua yang dibutuhkan.

## 🚀 **Panduan Setup Cepat (dengan XAMPP)**

Ikuti langkah-langkah detail ini untuk meluncurkan aplikasi To-Do List di komputer lokal Anda:

### Langkah 1: Persiapan Arena Proyek Anda

1.  **Dapatkan Sumber Kode:**
    * Unduh repositori ini sebagai file ZIP dan ekstrak isinya ke lokasi pilihan Anda. 📂

2.  **Tempatkan di XAMPP `htdocs`:**
    * Buka folder instalasi XAMPP Anda (misalnya, `C:\xampp\` di Windows atau `/Applications/XAMPP/` di macOS).
    * Navigasikan ke folder `htdocs` di dalamnya (contoh: `C:\xampp\htdocs\`).
    * Buat folder baru untuk proyek Anda di `htdocs`, beri nama yang rapi dan tanpa spasi, contoh: `my_todo_app`.
    * Salin seluruh isi proyek yang Anda unduh (termasuk `index.php`, `assets`, `classes`, `database`, dan `todo-app` folder) ke dalam folder `my_todo_app` ini.

    **Struktur Final Proyek Anda akan terlihat rapi seperti ini:**
    ```
    C:\xampp\htdocs\my_todo_app\
    ├── index.php           ⬅️ Ini adalah pintu gerbang aplikasi Anda
    ├── assets/
    │   └── css/
    │       └── style.css   ⬅️ Sentuhan estetik ada di sini
    ├── classes/
    │   └── TodoManager.php
    ├── database/
    │   └── includes/
    │   └── sql/
    │       └── todo_database.sql ⬅️ Skema database Anda
    └── todo-app/
        └── config/
            ├── database.php    ⬅️ Kunci koneksi database
            └── index.php
    ```

### Langkah 2: Mengaktifkan Data Core Anda (Database MySQL)

1.  **Hidupkan XAMPP Engines:**
    * Buka "XAMPP Control Panel".
    * Klik tombol **Start** pada modul **Apache** dan **MySQL**. Pastikan keduanya menyala hijau! ✅

2.  **Akses Pusat Kontrol Database (phpMyAdmin):**
    * Di browser web Anda, buka `http://localhost/phpmyadmin/`.

3.  **Impor Jiwa Database (Skema SQL):**
    * Di halaman phpMyAdmin, klik tab **Import** (atau "Impor") di bagian atas.
    * Klik tombol **Choose File** (atau "Pilih File") dan navigasikan ke file `todo_database.sql` dari `my_todo_app/database/sql/` Anda.
    * Biarkan opsi lain pada pengaturan default.
    * Klik tombol **Go** (atau "Kirim") di bagian bawah halaman.
    * **Penting:** Jika Anda mendapatkan error `Can't create database 'todo_app'; database exists`, itu berarti database sudah ada.
        * **Solusi Cepat:** Di phpMyAdmin, klik database `todo_app` di panel kiri. Pilih tab **Operations** (Operasi), lalu klik **Drop the database (DROP DATABASE)** (Jatuhkan basis data). Setelah itu, ulangi langkah impor.
        * **Alternatif:** Anda bisa edit file `todo_database.sql` dan hapus baris `CREATE DATABASE todo_app;` dan `USE todo_app;` jika Anda hanya ingin membuat/memperbarui tabel saja.

### Langkah 3: Kalibrasi Sistem (Konfigurasi PHP)

Pastikan file-file PHP Anda dikonfigurasi dengan benar untuk terhubung ke database.

1.  **Atur Koneksi Database (`todo-app/config/database.php`):**
    * Buka file `todo-app/config/database.php` dengan editor teks Anda.
    * Verifikasi kredensial database. Untuk instalasi XAMPP standar, ini biasanya sudah benar:
        ```php
        class Database {
            private $host = 'localhost';
            private $db_name = 'todo_app'; // Pastikan ini sama dengan nama database yang diimpor
            private $username = 'root';
            private $password = ''; // Kosong secara default untuk 'root' di XAMPP
            // ... (sisa kode) ...
        }
        ```
    * **Jangan lupa simpan!** 💾

2.  **Verifikasi Jalur Antar File (Krusial!):**
    Ini adalah area yang paling sering menyebabkan *bug*. Pastikan jalur `require_once` dan `include` Anda sudah benar.

    * **`index.php` (di root proyek):**
        ```php
        require_once __DIR__ . '/todo-app/config/database.php';
        require_once __DIR__ . '/classes/TodoManager.php';
        // ...
        header('Location: http://localhost/my_todo_app/'); // Ganti dengan nama folder proyek Anda!
        // ...
        ```
    * **`classes/TodoManager.php`:**
        ```php
        // Hapus atau komentari baris require_once database.php di sini
        // require_once __DIR__ . '/../todo-app/config/database.php';
        // ...
        ```
        **Catatan Penting:** Pada versi `TodoManager.php` terakhir yang saya berikan, baris `require_once database.php` di dalam `TodoManager.php` sudah dihapus/dikomentari karena objek koneksi dilewatkan melalui konstruktor. Pastikan ini juga dilakukan di file Anda.
    * **`todo-app/config/index.php`:**
        ```php
        require_once __DIR__ . '/database.php';                 // database.php ada di direktori yang sama
        require_once __DIR__ . '/../../classes/TodoManager.php'; // Path ke TodoManager.php dari todo-app/config/
        // ...
        header('Location: http://localhost/my_todo_app/'); // Ganti dengan nama folder proyek Anda!
        // ...
        ```
    * **Simpan semua file** yang Anda modifikasi! ✅

### Langkah 4: Meluncurkan Aplikasi Anda! 🚀

1.  **Buka Browser Web Anda.**
2.  **Ketikkan URL ini:** `http://localhost/my_todo_app/` (Ingat, ganti `my_todo_app` jika nama folder proyek Anda berbeda!).

Selamat! Aplikasi To-Do List Anda kini hidup dan siap membantu Anda mengatur tugas dengan gaya neon yang menawan. ✨

---

## 🎨 **Kustomisasi Estetika**

Ingin sentuhan yang lebih personal? Anda bisa memodifikasi `assets/css/style.css` untuk mengubah tema warna, efek neon, atau bahkan menambahkan latar belakang motif anime pilihan Anda!

* **Variabel Warna:** Lihat bagian `:root` di `style.css` untuk mengubah warna utama (`--bg-dark`, `--neon-blue`, `--neon-pink`, dll.).
* **Font:** Ganti `Oxanium` atau `Montserrat` di `@import` dan `font-family` jika Anda punya font lain yang disukai.
* **Latar Belakang Anime (Opsional):**
    1.  Buat folder `assets/img/` di proyek Anda.
    2.  Tempatkan gambar motif atau karakter anime kesukaan Anda di sana (misal: `anime_bg.png`).
    3.  Di `style.css`, cari bagian `body` dan aktifkan/sesuaikan properti `background-image`:
        ```css
        body {
            /* ... kode lainnya ... */
            background-image: url('../assets/img/anime_bg.png'); /* Sesuaikan path! */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-blend-mode: overlay; /* Eksperimen dengan 'multiply', 'screen', dll. */
            opacity: 0.8; /* Sesuaikan tingkat transparansi */
        }
        ```

Selamat menikmati To-Do List Neon Anda! 🎉
