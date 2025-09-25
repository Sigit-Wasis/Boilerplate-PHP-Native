<?php
require_once __DIR__ . '/config/database.php';

$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);

// Routing sederhana
switch ($request) {
    case '/':
    case '/index.php':
        require_once __DIR__ . '/modules/home/index.php';
        break;

    // Modul Login
    case '/login':
    case '/login/index':
        require_once __DIR__ . '/modules/login/index.php';
        break;

    case '/login/proses':
        require_once __DIR__ . '/modules/login/proses.php';
        break;

    // Modul Logout
    case '/logout':
        require_once __DIR__ . '/modules/login/logout.php';
        break;

    // Modul profil
    case '/profil':
    case '/profil/index':
        require_once __DIR__ . '/modules/profil/index.php';
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

    // Modul post
    case '/post':
    case '/post/index':
        require_once __DIR__ . '/modules/post/index.php';
        break;
    case '/post/create':
        require_once __DIR__ . '/modules/post/create.php';
        break;
    case '/post/store': // aksi simpan post baru
        require_once __DIR__ . '/modules/post/store.php';
        break;
    case '/post/edit':
        require_once __DIR__ . '/modules/post/edit.php';
        break;
    case '/post/update': // aksi update post
        require_once __DIR__ . '/modules/post/update.php';
        break;
    case '/post/delete':
        require_once __DIR__ . '/modules/post/delete.php';
        break;

    default:
        http_response_code(404);
        echo "Halaman tidak ditemukan!";
        break;
}
?>