<?php
// instagram_dm_clone.php
// Bagian ini adalah BLOK KODE PHP. Di sini Anda bisa menaruh logika server,
// seperti mengambil data pengguna dari database, atau menentukan variabel.

// Contoh variabel PHP untuk membuat konten lebih dinamis:
$username = "dwiirhma";
$request_count = 7; 
$chat_list_title = "Pesan";

// Catatan: Jika Anda tidak memerlukan logika PHP saat ini,
// Anda bisa menghapus blok '<?php ... ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram DM Clone (PHP)</title>
    <style>
        /* CSS Dasar - Mode Gelap */
        :root {
            --bg-dark: #121212;
            --bg-medium: #1e1e1e;
            --bg-light: #2c2c2c;
            --text-primary: #f5f5f5;
            --text-secondary: #a8a8a8;
            --border-color: #363636;
            --instagram-blue: #0095f6;
            --accent-green: #38b000;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* --- Layout & Komponen CSS lainnya (SAMA SEPERTI SEBELUMNYA) --- */
        .container {
            display: flex;
            width: 100%;
        }

        .sidebar { /* ... CSS Navigasi Samping ... */ width: 72px; background-color: var(--bg-medium); border-right: 1px solid var(--border-color); padding-top: 20px; display: flex; flex-direction: column; align-items: center; }
        .sidebar-icon { /* Placeholder ikon */ width: 28px; height: 28px; background-color: var(--text-secondary); }
        .inbox { /* ... CSS Kotak Masuk ... */ width: 390px; background-color: var(--bg-medium); border-right: 1px solid var(--border-color); display: flex; flex-direction: column; padding-top: 10px; }
        .inbox-header { padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .search-box input { width: 100%; padding: 8px 10px; border: none; border-radius: 8px; background-color: var(--bg-light); color: var(--text-primary); }
        .notes-section { padding: 10px 20px; border-bottom: 1px solid var(--border-color); }
        .note-circle { width: 60px; height: 60px; border-radius: 50%; background-color: var(--bg-light); border: 2px solid var(--border-color); }
        .chat-item { display: flex; align-items: center; padding: 12px 20px; cursor: pointer; position: relative; }
        .chat-item:hover, .chat-item.active { background-color: var(--bg-light); }
        .chat-avatar { width: 50px; height: 50px; border-radius: 50%; margin-right: 15px; position: relative; }
        .online-dot { position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background-color: var(--accent-green); border-radius: 50%; border: 2px solid var(--bg-medium); }
        .unread-indicator { position: absolute; right: 20px; width: 8px; height: 8px; background-color: var(--instagram-blue); border-radius: 50%; }
        .message-panel { /* ... CSS Panel Pesan Kanan ... */ flex-grow: 1; display: flex; justify-content: center; align-items: center; flex-direction: column; text-align: center; padding: 20px; }
        .message-panel button { background-color: var(--instagram-blue); color: var(--text-primary); border: none; padding: 8px 15px; border-radius: 8px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>

    <div class="container">
        <div class="sidebar">
            </div>

        <div class="inbox">
            <div class="inbox-header">
                <h2><?= $username ?></h2> 
                <span style="font-size: 20px; cursor: pointer;">...</span> 
            </div>
            
            <div class="search-box">
                <input type="text" placeholder="Cari">
            </div>
            
            <div class="notes-section">
                <h3>Catatan</h3>
                </div>
            
            <div class="chat-list-section">
                <div class="chat-list-header">
                    <h3><?= $chat_list_title ?></h3>
                    <span class="request-link">Permintaan (<?= $request_count ?>)</span>
                </div>
                
                <div class="chat-item active">
                    </div>
                <div class="chat-item">
                    </div>
                </div>
        </div>

        <div class="message-panel">
            <h1>Pesan Anda</h1>
            <p>Kirim foto dan pesan pribadi ke teman atau grup</p>
            <button>Kirim Pesan</button>
        </div>
    </div>

</body>
</html>