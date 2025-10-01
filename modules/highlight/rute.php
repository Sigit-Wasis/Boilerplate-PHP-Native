<?php
// Tambahkan ini ke file routes.php Anda

// Route untuk highlight
if ($uri === '/highlight') {
    require_once _DIR_ . '/controllers/HighlightController.php';
    exit;
}