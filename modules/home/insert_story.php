<?php
header('Content-Type: application/json');

// Ambil data JSON dari request body
$data = json_decode(file_get_contents('php://input'), true);

echo($data);

if (!$data || empty($data['image'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

// Contoh: Ambil user_id dari session (atau sesuaikan dengan sistem login kamu)
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // fallback user_id 1

$image = $data['image'];
$textElements = json_encode($data['textElements'] ?? []);
$created_at = date('Y-m-d H:i:s');

// // Simpan ke database (contoh tabel: stories)
// $stmt = $conn->prepare("INSERT INTO story (user_id, image, text_elements, created_at) VALUES (?, ?, ?, ?)");
// $stmt->bind_param("isss", $user_id, $image, $textElements, $created_at);

echo "User ID: $user_id\n";
echo "Image: $image\n";

// if ($stmt->execute()) {
//     echo json_encode(['success' => true]);
// } else {
//     echo json_encode(['success' => false, 'message' => 'Gagal menyimpan ke database']);
// }

// $stmt->close();
// $conn->close();