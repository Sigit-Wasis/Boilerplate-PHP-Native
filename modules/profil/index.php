<?php
session_start();
require_once __DIR__ . '/../../includes/header.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit;
}
?>

<!-- Main Content -->
<div class="main-content">
    <h2>Profil Saya</h2>
    <p>Selamat datang di halaman profil Anda!</p>

    <table>
        <tr>
            <th>Nama</th>
            <td><?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? '-') ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= htmlspecialchars($_SESSION['user']['email'] ?? '-') ?></td>
        </tr>
    </table>

    <a href="/logout" class="btn-logout" style="margin-top: 20px; display: block; text-decoration: none;">Logout</a>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
