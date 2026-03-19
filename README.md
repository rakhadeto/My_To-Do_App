# ⚔️ SAO Quest Manager — My To-Do App

> Aplikasi manajemen tugas harian berbasis RPG dengan tema **Sword Art Online** — dibangun menggunakan PHP & MySQL. Selesaikan quest, kumpulkan XP & Gold, naik level, dan tebus reward!

---

## ✨ Fitur Utama

- **🎮 Sistem RPG** — Karakter dengan HP, XP, Level, dan Gold yang dinamis
- **⚡ Habits** — Tugas berulang dengan tombol `+` (dapat XP & Gold) dan `−` (kurangi HP)
- **🗓 Dailies** — Tugas harian yang otomatis reset setiap hari
- **📋 To-Do** — Quest satu kali dengan reward XP & Gold saat diselesaikan
- **◈ Item Shop** — Tukar Gold untuk reward nyata yang kamu tentukan sendiri
- **💀 Death System** — HP habis? Level turun, XP & Gold hilang. Hati-hati!
- **🎵 BGM Player** — Background music ala game RPG
- **🎬 Video Background** — Latar video animasi yang imersif
- **✨ Animasi SAO** — Hex avatar, cursor custom, notifikasi pop-up, modal Level Up & Game Over

---

## 🛠️ Teknologi yang Digunakan

![PHP](https://img.shields.io/badge/-PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/-HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/-CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/-JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

---

## 📁 Struktur Project

```
My_To-Do_App/
├── index.php           # Halaman utama aplikasi
├── database.php        # Konfigurasi koneksi database
├── TodoManager.php     # Logic habits, dailies, todos, rewards & stats
├── style.css           # Stylesheet tema SAO
├── script.js           # Cursor, animasi & interaksi UI
├── todo_database.sql   # Struktur & data awal database
├── avatar.png          # Foto profil karakter
├── bg.mp4              # Video background
└── musik.mp3           # Background music
```

---

## 🚀 Cara Menjalankan

### Prasyarat
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL)

### Langkah-langkah

**1. Clone atau download repo ini**
```bash
git clone https://github.com/rakhadeto/My_To-Do_App.git
```

**2. Pindahkan ke folder XAMPP**
```
C:\xampp\htdocs\My_To-Do_App\
```

**3. Jalankan XAMPP**
- Buka XAMPP Control Panel
- Klik **Start** pada **Apache** dan **MySQL**

**4. Import database**
- Buka `localhost/phpmyadmin`
- Buat database baru bernama `todo_app`
- Klik tab **Import** → pilih file `todo_database.sql` → klik **Go**

**5. Buka aplikasi**
```
localhost/My_To-Do_App
```

---

## 🎮 Cara Bermain

| Aksi | Reward |
|------|--------|
| ✅ Selesaikan To-Do | +20 XP, +10 Gold |
| ✅ Selesaikan Daily | +10 XP, +3 Gold |
| ⚡ Habit positif (`+`) | +15 XP, +5 Gold |
| ❌ Habit negatif (`−`) | −10 HP |
| ❌ Batal To-Do | −20 XP, −10 Gold |
| 💀 HP = 0 | Level turun, XP & Gold hilang |
| 🆙 XP penuh | Level Up, HP full, Max HP naik |

---

## 📄 Lisensi

Project ini bersifat open source di bawah lisensi [MIT](LICENSE).

---

<p align="center">⚔️ Dibuat dengan semangat oleh <a href="https://github.com/rakhadeto">Naufal Rakhadeto</a> · 2026 ⚔️</p>
<p align="center"><em>"In this world, it's either kill or be killed." — Kirito, SAO</em></p>
