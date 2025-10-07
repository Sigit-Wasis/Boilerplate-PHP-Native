<?php
require_once __DIR__. '/../config/database.php';

// Fungsi CRUD: Create
function createUser(string $name, string $email): bool {
    $conn = getDBConnection();
    
    $name = htmlspecialchars(stripslashes(trim($name)));
    $email = htmlspecialchars(stripslashes(trim($email)));
    
    $sql = "INSERT INTO users (name, email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $email);
    
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Fungsi find user by ID
function getUserById(int $id): ?array {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $result;
}

// Fungsi CRUD: Update
function updateUser(int $id, string $name, string $email): bool {
    $conn = getDBConnection();

    $name = htmlspecialchars(stripslashes(trim($name)));
    $email = htmlspecialchars(stripslashes(trim($email)));
    $id = (int)$id;

    $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $email, $id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

// Fungsi CRUD: Delete
function deleteUser(int $id): bool {
    $conn = getDBConnection();
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

// Fungsi CRUD: Read
function getUsers() {
    $conn = getDBConnection();
    
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    
    $users = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    
    $conn->close();
    return $users;
}

function getUsersWithFollowingStatus($current_user_id) {
    // Pastikan Anda mendapatkan koneksi database
    $conn = getDBConnection();
    
    // Query SQL menggunakan LEFT JOIN untuk menggabungkan tabel users dan follow
    // Kami memeriksa apakah ada baris di tabel 'follow' di mana 'follower_id'
    // adalah pengguna yang sedang login ($current_user_id) dan 'followed_id'
    // adalah pengguna yang sedang kita ambil datanya (u.id).
    $sql = "
        SELECT 
            u.*,
            f.follower_id
        FROM 
            users u
        LEFT JOIN 
            follows f ON u.id = f.following_id AND f.follower_id = ?
        WHERE
            u.id != ?  -- Opsional: Jangan tampilkan diri sendiri
    ";

    // Gunakan Prepared Statement untuk keamanan!
    $stmt = $conn->prepare($sql);
    
    // Binding parameter untuk placeholder '?'
    // 'ii' berarti dua variabel integer
    $stmt->bind_param("ii", $current_user_id, $current_user_id);
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Logika untuk menambahkan kolom 'is_following'
            // Jika kolom f.follower_id (dari tabel 'follow') tidak NULL, 
            // berarti ada data follow yang cocok, sehingga is_following = true.
            $row['is_following'] = ($row['follower_id'] !== null);
            
            // Hapus kolom helper 'follower_id' sebelum dikirimkan ke frontend
            unset($row['follower_id']); 
            
            $users[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $users;
}


// Fungsi authentikasi
function authenticateUser($email, $password) {
    $conn = getDBConnection();
    
    function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
    $email = sanitizeInput($email);
    $password = sanitizeInput($password);
    
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, md5($password)); // Asumsikan password disimpan dalam bentuk hash MD5
    $stmt->execute();
    
    $result = $stmt->get_result();
    $user = null;
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }
    
    $stmt->close();
    $conn->close();

    return $user;
}


?>