# PHP Boilerplate (Native PHP)

Selamat datang di **PHP Boilerplate**! Proyek ini adalah template sederhana untuk membangun aplikasi web menggunakan **PHP Native** (tanpa framework). Boilerplate ini dirancang dengan struktur folder yang terorganisir, modular, dan mudah dikembangkan, cocok untuk proyek skala kecil hingga menengah.

## 📂 Struktur Folder

Berikut adalah struktur folder proyek yang rapi dan terorganisir:

```
php_boilerplate/
├── config/                  # Konfigurasi aplikasi
│   └── database.php         # Pengaturan koneksi database (MySQL/MariaDB)
├── database/                # Skema dan data awal database
│   └── schema.sql           # Struktur tabel database
├── includes/                # File partial untuk UI yang dapat digunakan kembali
│   ├── header.php           # Template header
│   └── footer.php           # Template footer
├── models/                  # Model untuk interaksi dengan database
│   ├── Jabatan.php          # Model untuk entitas Jabatan
│   └── User.php             # Model untuk entitas User
├── modules/                 # Logika bisnis / controller sederhana
│   ├── jabatan.php          # Modul untuk pengelolaan jabatan
│   └── user.php             # Modul untuk pengelolaan user
├── public/                  # Folder akses publik (root web server)
│   ├── css/                 # File CSS
│   │   └── style.css        # CSS global
│   ├── js/                  # File JavaScript
│   │   └── main.js          # JavaScript global
│   └── index.php            # Entry point aplikasi
└── README.md                # Dokumentasi proyek
```

## ⚙️ Instalasi & Setup

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda:

1. **Clone Repository**:
    ```bash
    git clone https://github.com/username/php_boilerplate.git
    cd php_boilerplate
    ```

2. **Buat Database**:
    - Buat database baru di MySQL/MariaDB (misalnya: `php_boilerplate`).
    - Import file `database/schema.sql` ke database Anda untuk membuat struktur tabel.

3. **Konfigurasi Koneksi Database**:
    - Buka file `config/database.php`.
    - Sesuaikan pengaturan koneksi database dengan kredensial lokal Anda:
      ```php
      define('DB_HOST', 'localhost');
      define('DB_USER', 'root');
      define('DB_PASS', '');
      define('DB_NAME', 'php_boilerplate');
      ```

4. **Jalankan Aplikasi**:
    - Arahkan document root web server (Apache/Nginx) ke folder `public/`.
    - Alternatifnya, gunakan PHP built-in server:
      ```bash
      php -S localhost:8000 -t public
      ```

5. **Akses Aplikasi**:
    - Buka browser dan kunjungi: [http://localhost:8000](http://localhost:8000).

## 🗂️ Struktur Logika

- **Models**: Berisi fungsi CRUD (Create, Read, Update, Delete) untuk berinteraksi langsung dengan database (contoh: `User.php`, `Jabatan.php`).
- **Modules**: Mengatur logika bisnis dan menghubungkan antara model dan tampilan (contoh: `user.php`, `jabatan.php`).
- **Includes**: Berisi file partial seperti `header.php` dan `footer.php` untuk elemen UI yang dapat digunakan kembali.
- **Public**: Berisi file yang dapat diakses oleh browser, seperti `index.php`, file CSS, dan JavaScript.

## 🚀 Rencana Pengembangan

Berikut adalah fitur yang direncanakan untuk pengembangan lebih lanjut:
- Menambahkan folder `helpers/` untuk fungsi utilitas global (contoh: `sanitizeInput()`, `formatDate()`).
- Mengimplementasikan sistem autentikasi dan login sederhana.
- Menerapkan struktur MVC (Model-View-Controller) yang lebih terdefinisi.
- Menambahkan sistem routing sederhana untuk navigasi yang lebih baik.
- Mendukung fitur keamanan tambahan seperti proteksi CSRF dan validasi input.

## 🛠️ Teknologi yang Digunakan

- **PHP**: Bahasa utama untuk logika server-side.
- **MySQL/MariaDB**: Database untuk penyimpanan data.
- **HTML/CSS/JavaScript**: Untuk antarmuka pengguna.

## 📝 Lisensi

Proyek ini dilisensikan di bawah **MIT License**, bebas digunakan untuk keperluan belajar, pengembangan aplikasi internal, atau proyek open-source lainnya. Silakan modifikasi sesuai kebutuhan Anda!

## 🤝 Kontribusi

Kami menyambut kontribusi! Jika Anda ingin berkontribusi:
1. Fork repository ini.
2. Buat branch baru (`git checkout -b feature/nama-fitur`).
3. Commit perubahan Anda (`git commit -m 'Menambahkan fitur X'`).
4. Push ke branch Anda (`git push origin feature/nama-fitur`).
5. Buat Pull Request di GitHub.

## 📬 Kontak

Jika ada pertanyaan atau saran, silakan buka issue di repository ini atau hubungi kami melalui [email@example.com](mailto:email@example.com).