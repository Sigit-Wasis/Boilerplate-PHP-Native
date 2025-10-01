<?php
require_once __DIR__ . '/../config/database.php'; // sesuaikan path ke database.php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(["status" => "error", "message" => "User belum login"]);
    exit;
}

$user_id = $_SESSION['user']['id'];
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

if ($post_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Post tidak valid"]);
    exit;
}

    $conn = getDBConnection();

// Cek apakah user sudah like post ini
$stmt = $conn->prepare("SELECT id FROM likes WHERE post_id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Jika sudah like → hapus (unlike)
    $delete = $conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
    $delete->bind_param("ii", $post_id, $user_id);
    $delete->execute();
    $action = "unliked";
} else {
    // Jika belum → insert like
    $insert = $conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
    $insert->bind_param("ii", $post_id, $user_id);
    $insert->execute();
    $action = "liked";
}

// Hitung ulang total likes
$count = $conn->prepare("SELECT COUNT(*) as total FROM likes WHERE post_id = ?");
$count->bind_param("i", $post_id);  // ikat parameter post_id
$count->execute();
$res = $count->get_result()->fetch_assoc();
$totalLikes = $res['total'];


header('Location: /home/index');
exit;