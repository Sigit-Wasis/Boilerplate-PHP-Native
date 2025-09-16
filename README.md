# PHP Boilerplate (Native PHP)

Proyek ini adalah boilerplate sederhana untuk membangun aplikasi web menggunakan **PHP Native** (tanpa framework).  
Struktur folder sudah dipisahkan berdasarkan `config`, `models`, `modules`, `public`, dll agar lebih rapi dan mudah dikembangkan.

## 📂 Struktur Folder

php_boilerplate/
│
├── config/ # Konfigurasi aplikasi
│ └── database.php # File koneksi database (MySQL/MariaDB)
│
├── database/ # Skema & data awal database
│ └── schema.sql # Struktur tabel database
│
├── includes/ # File partial (reusable UI)
│ ├── footer.php
│ └── header.php
│
├── models/ # File model (berhubungan dengan database)
│ ├── Jabatan.php
│ └── User.php
│
├── modules/ # Modul bisnis / controller sederhana
│ ├── jabatan.php
│ └── user.php
│
├── public/ # Akses publik (root web server)
│ ├── css/
│ │ └── style.css # CSS global
│ ├── js/
│ │ └── main.js # JavaScript global
│ └── index.php # Entry point aplikasi
│
└── README.md # Dokumentasi proyek

## ⚙️ Instalasi & Setup

1. Clone repository:
    ```bash
    git clone https://github.com/username/php_boilerplate.git
    cd php_boilerplate
    ```

2. Import database:

Buat database baru di MySQL/MariaDB.

3. Import file database/schema.sql.

Konfigurasi koneksi database:

Edit file config/database.php dan sesuaikan dengan username/password DB lokal kamu.

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'php_boilerplate');
```

4. Jalankan aplikasi:

Arahkan document root web server ke folder public/.

Atau jalankan PHP built-in server:

```bash
php -S localhost:8000 -t public
```

5. Buka di browser:

http://localhost:8000


## 🗂️ Struktur Logika

Models → berisi fungsi CRUD langsung ke database (misal User.php, Jabatan.php).
Modules → mengatur logika bisnis dan interaksi dengan models.
Includes → file header/footer reusable.
Public → hanya file yang bisa diakses browser (HTML, CSS, JS, index.php).

## 🚀 Rencana Pengembangan

 Tambah folder helpers/ untuk fungsi global (misalnya sanitizeInput()).
 Implementasi sistem login & autentikasi sederhana.
 Struktur MVC lebih rapih (Controller, Model, View).
 Penambahan sistem routing sederhana.

## 📝 Lisensi

Proyek ini bebas digunakan untuk belajar maupun pengembangan aplikasi internal.
