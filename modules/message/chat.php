<?php
// test_api.php - Letakkan file ini di folder yang sama dengan instagram_dm_clone.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Test API & Database</h1>";
echo "<style>body{font-family:Arial;padding:20px;background:#1a1a1a;color:#fff;} .success{color:#0f0;} .error{color:#f00;} .info{color:#0af;} pre{background:#000;padding:10px;border-radius:5px;overflow:auto;}</style>";

// 1. Cek PHP Version
echo "<h2>1️⃣ PHP Version</h2>";
echo "<p class='success'>✅ PHP Version: " . phpversion() . "</p>";

// 2. Cek File Structure
echo "<h2>2️⃣ File Structure</h2>";
$files = [
    'config/database.php',
    'api/chat.php',
    'instagram_dm_clone.php'
];

foreach($files as $file) {
    $path = __DIR__ . '/' . $file;
    if(file_exists($path)) {
        echo "<p class='success'>✅ $file exists</p>";
    } else {
        echo "<p class='error'>❌ $file NOT FOUND (Path: $path)</p>";
    }
}

// 3. Cek Folder Uploads
echo "<h2>3️⃣ Upload Folder</h2>";
$upload_dir = __DIR__ . '/uploads/chat_images/';
if(is_dir($upload_dir)) {
    echo "<p class='success'>✅ Upload folder exists: $upload_dir</p>";
    if(is_writable($upload_dir)) {
        echo "<p class='success'>✅ Upload folder is writable</p>";
    } else {
        echo "<p class='error'>❌ Upload folder is NOT writable</p>";
        echo "<p class='info'>💡 Run: chmod -R 777 uploads</p>";
    }
} else {
    echo "<p class='error'>❌ Upload folder NOT found</p>";
    echo "<p class='info'>Creating folder...</p>";
    if(mkdir($upload_dir, 0777, true)) {
        echo "<p class='success'>✅ Folder created successfully</p>";
    } else {
        echo "<p class='error'>❌ Failed to create folder</p>";
    }
}

// 4. Test Database Connection
echo "<h2>4️⃣ Database Connection</h2>";

if(file_exists(__DIR__ . '/config/database.php')) {
    require_once __DIR__ . '/config/database.php';
    
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        if($db) {
            echo "<p class='success'>✅ Database connected successfully</p>";
            
            // Cek tabel users
            try {
                $stmt = $db->query("SELECT COUNT(*) as count FROM users");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<p class='success'>✅ Table 'users': " . $result['count'] . " rows</p>";
                
                // Tampilkan data users
                $stmt = $db->query("SELECT * FROM users LIMIT 5");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "<details><summary>👥 Users Data (click to expand)</summary>";
                echo "<pre>" . print_r($users, true) . "</pre>";
                echo "</details>";
                
            } catch(PDOException $e) {
                echo "<p class='error'>❌ Table 'users' error: " . $e->getMessage() . "</p>";
                echo "<p class='info'>💡 Pastikan tabel sudah dibuat. Jalankan SQL schema!</p>";
            }
            
            // Cek tabel messages
            try {
                $stmt = $db->query("SELECT COUNT(*) as count FROM messages");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<p class='success'>✅ Table 'messages': " . $result['count'] . " rows</p>";
                
                // Tampilkan data messages
                $stmt = $db->query("SELECT * FROM messages LIMIT 5");
                $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "<details><summary>💬 Messages Data (click to expand)</summary>";
                echo "<pre>" . print_r($messages, true) . "</pre>";
                echo "</details>";
                
            } catch(PDOException $e) {
                echo "<p class='error'>❌ Table 'messages' error: " . $e->getMessage() . "</p>";
                echo "<p class='info'>💡 Pastikan tabel sudah dibuat. Jalankan SQL schema!</p>";
            }
            
        } else {
            echo "<p class='error'>❌ Database connection failed</p>";
        }
        
    } catch(Exception $e) {
        echo "<p class='error'>❌ Database error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='error'>❌ config/database.php not found</p>";
}

// 5. Test API Endpoints
echo "<h2>5️⃣ API Endpoints Test</h2>";

if(file_exists(__DIR__ . '/api/chat.php')) {
    echo "<p class='success'>✅ API file exists</p>";
    
    // Test berbagai endpoint
    $base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/api/chat.php';
    
    $tests = [
        'Get Chats' => $base_url . '?action=get_chats&user_id=1',
        'Get Messages' => $base_url . '?action=get&user1=1&user2=2',
    ];
    
    foreach($tests as $name => $url) {
        echo "<h3>Testing: $name</h3>";
        echo "<p class='info'>URL: $url</p>";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if($http_code == 200) {
            echo "<p class='success'>✅ HTTP Status: $http_code</p>";
            $json = json_decode($response, true);
            if($json) {
                echo "<p class='success'>✅ Valid JSON response</p>";
                echo "<details><summary>Response (click to expand)</summary>";
                echo "<pre>" . print_r($json, true) . "</pre>";
                echo "</details>";
            } else {
                echo "<p class='error'>❌ Invalid JSON response</p>";
                echo "<pre>" . htmlspecialchars($response) . "</pre>";
            }
        } else {
            echo "<p class='error'>❌ HTTP Status: $http_code</p>";
            echo "<p class='error'>Response: " . htmlspecialchars($response) . "</p>";
        }
    }
    
} else {
    echo "<p class='error'>❌ API file not found</p>";
}

// 6. Insert Test Data
echo "<h2>6️⃣ Insert Test Data</h2>";

if(isset($db) && $db) {
    echo "<form method='post' style='background:#333;padding:15px;border-radius:5px;margin:10px 0;'>";
    echo "<p>Klik tombol di bawah untuk menambahkan data dummy:</p>";
    echo "<button type='submit' name='insert_test' style='background:#0095f6;color:#fff;padding:10px 20px;border:none;border-radius:5px;cursor:pointer;'>Insert Test Data</button>";
    echo "</form>";
    
    if(isset($_POST['insert_test'])) {
        try {
            // Insert users jika belum ada
            $stmt = $db->query("SELECT COUNT(*) as count FROM users");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($result['count'] == 0) {
                $db->exec("INSERT INTO users (username) VALUES 
                    ('Wellysa verdalena'),
                    ('Putri'),
                    ('sintia'),
                    ('.._._..') 
                ");
                echo "<p class='success'>✅ Test users inserted</p>";
            }
            
            // Insert messages
            $db->exec("INSERT INTO messages (sender_id, receiver_id, message) VALUES 
                (2, 1, 'Hai, kamu di mana?'),
                (1, 2, 'Aku di rumah, kenapa?'),
                (3, 1, 'Cek DM!'),
                (1, 4, 'Lihat lampiranku ya')
            ");
            
            echo "<p class='success'>✅ Test messages inserted successfully!</p>";
            echo "<p class='info'>🔄 Refresh halaman untuk melihat data baru</p>";
            
        } catch(PDOException $e) {
            echo "<p class='error'>❌ Insert failed: " . $e->getMessage() . "</p>";
        }
    }
}

// 7. Summary & Next Steps
echo "<h2>7️⃣ Summary & Next Steps</h2>";
echo "<div style='background:#333;padding:15px;border-radius:5px;'>";
echo "<h3>Checklist:</h3>";
echo "<ul>";
echo "<li>✅ Semua file ada? (Cek bagian 2)</li>";
echo "<li>✅ Database terkoneksi? (Cek bagian 4)</li>";
echo "<li>✅ Tabel users & messages ada? (Cek bagian 4)</li>";
echo "<li>✅ Ada data di tabel? (Klik 'Insert Test Data' jika kosong)</li>";
echo "<li>✅ API endpoint berfungsi? (Cek bagian 5)</li>";
echo "</ul>";

echo "<h3>🎯 Langkah Selanjutnya:</h3>";
echo "<ol>";
echo "<li>Buka <a href='instagram_dm_clone.php' style='color:#0af;'>instagram_dm_clone.php</a></li>";
echo "<li>Tekan <strong>F12</strong> untuk membuka Console</li>";
echo "<li>Lihat apakah ada error di Console</li>";
echo "<li>Coba kirim pesan dan cek apakah tersimpan</li>";
echo "</ol>";

echo "<h3>❓ Masih Bermasalah?</h3>";
echo "<p>Jika masih error, screenshot:</p>";
echo "<ul>";
echo "<li>Hasil test ini (keseluruhan halaman)</li>";
echo "<li>Console browser (F12 → Console tab)</li>";
echo "<li>Network tab saat kirim pesan (F12 → Network tab)</li>";
echo "</ul>";
echo "</div>";

echo "<hr>";
echo "<p style='text-align:center;color:#666;'>Test completed at " . date('Y-m-d H:i:s') . "</p>";
?>