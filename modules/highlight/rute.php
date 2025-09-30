<?php
// Tambahkan ini ke file routes.php Anda

// Route untuk highlight
if ($uri === '/highlight') {
    require_once __DIR__ . '/controllers/HighlightController.php';
    exit;
}