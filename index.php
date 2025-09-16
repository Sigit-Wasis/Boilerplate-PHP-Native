<?php
require_once __DIR__ . '/config/database.php';

$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);

// Routing sederhana
switch ($request) {
    case '/':
    case '/index.php':
        require_once __DIR__ . '/includes/header.php';
        echo "<h2>Selamat datang di Aplikasi PHP</h2>";
        require_once __DIR__ . '/includes/footer.php';
        break;

    // Modul User
    case '/user':
    case '/user/index':
        require_once __DIR__ . '/modules/users/index.php';
        break;
    case '/user/create':
        require_once __DIR__ . '/modules/users/create.php';
        break;
    case '/user/store': // aksi simpan user baru
        require_once __DIR__ . '/modules/users/store.php';
        break;
    case '/user/edit':
        require_once __DIR__ . '/modules/users/edit.php';
        break;
    case '/user/update': // aksi update user
        require_once __DIR__ . '/modules/users/update.php';
        break;
    case '/user/delete':
        require_once __DIR__ . '/modules/users/delete.php';
        break;

    // Modul Jabatan
    case '/jabatan':
    case '/jabatan/index':
        require_once __DIR__ . '/modules/jabatan.php';
        break;

    default:
        http_response_code(404);
        echo "Halaman tidak ditemukan!";
        break;
}
