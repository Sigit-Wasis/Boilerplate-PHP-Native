<?php
session_start();

// Hapus semua variabel session
$_SESSION = [];

// Jika ada cookie session, hapus juga
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Hancurkan session di server
session_destroy();

// Redirect ke halaman login
header("Location: /login");
exit;
