<?php

require_once(__DIR__ . '/../../config/database.php'); // gunakan file database.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['image'])) {
        $conn = getDBConnection();
        $user_id = 1; // Ganti dengan user yang sedang login
        $image_base64 = $input['image']; // Simpan base64 langsung
        $text_elements = '';
        $story_type = 'public';

        $stmt = $conn->prepare("INSERT INTO story (user_id, image_path, text_elements, story_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $image_base64, $text_elements, $story_type);
        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'DB error']);
        }
        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'No image']);
    }
    exit;
}