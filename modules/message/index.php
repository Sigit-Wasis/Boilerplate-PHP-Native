<?php
require_once __DIR__ . '/../../models/Message.php';
require_once __DIR__ . '/../../models/User.php';
// instagram_dm_clone.php
// Bagian ini adalah BLOK KODE PHP. Di sini Anda bisa menaruh logika server,
// seperti mengambil data pengguna dari database, atau menentukan variabel.

// Contoh variabel PHP untuk membuat konten lebih dinamis:
$username = "Wellysa verdalena";
$request_count = 7; 
$chat_list_title = "Pesan";
$chat_partner_name = "Putri"; 
$chat_partner_status = "Aktif 10m lalu"; 

$users = getUser();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram DM Clone (Fixed Layout)</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* CSS Dasar - Mode Gelap */
        :root {
            --bg-dark: #000000;
            --bg-medium: #121212;
            --bg-light: #262626;
            --text-primary: #f5f5f5;
            --text-secondary: #a8a8a8;
            --border-color: #363636;
            --instagram-blue: #0095f6;
            --accent-green: #38b000;
            --bubble-friend: #262626; 
            --bubble-mine: #0095f6; 
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

        /* --- Layout Utama --- */
        .container {
            display: flex;
            width: 100%;
        }

        /* --- Sidebar Navigasi Kiri (72px) --- */
        .sidebar { 
            width: 72px; 
            background-color: var(--bg-dark); 
            border-right: 1px solid var(--border-color); 
            padding-top: 20px; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: space-between; 
            padding-bottom: 20px;
            flex-shrink: 0; 
        }
        .sidebar-top, .sidebar-bottom {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        .logo-text { font-weight: bold; font-size: 1.5rem; margin-bottom: 30px; }
        .sidebar-icon { 
            width: 48px; 
            height: 48px; 
            margin-bottom: 8px; 
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 24px;
            color: var(--text-primary); /* Default color untuk semua ikon container */
            transition: all 0.1s;
        }
        .sidebar-icon:hover {
            background-color: var(--bg-medium); /* Efek hover */
        }
        .sidebar-icon a {
            text-decoration: none; /* Hilangkan garis bawah pada link */
            color: inherit; /* Warisi warna dari parent (div.sidebar-icon) */
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        /* PERUBAHAN UTAMA: Menjamin warna ikon Home putih */
        .sidebar-icon .fa-home {
            color: #fff; /* Mengubah warna ikon Home menjadi putih */
        }

        .sidebar-icon.active { border: 2px solid var(--text-primary); }
        .profile-pic-container { width: 28px; height: 28px; border-radius: 50%; border: 2px solid var(--text-secondary); overflow: hidden; }
        .profile-pic-container img { width: 100%; height: 100%; object-fit: cover; }

        /* --- Inbox (Daftar Chat) --- */
        .inbox { 
            width: 390px; 
            background-color: var(--bg-dark); 
            border-right: 1px solid var(--border-color); 
            display: flex; 
            flex-direction: column; 
            padding-top: 10px; 
            overflow-y: auto; 
            flex-shrink: 0;
        }
        .inbox-header { padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .inbox-header h2 { font-size: 1.2rem; font-weight: 600; margin: 0; }
        .search-box { padding: 0 20px 10px; }
        .search-box input { width: 100%; padding: 8px 10px; border: none; border-radius: 8px; background-color: var(--bg-light); color: var(--text-primary); font-size: 0.9rem; text-align: left; }
        
        /* Notes Section */
        .notes-section { padding: 10px 0; margin: 0 20px; border-bottom: 1px solid var(--border-color); }
        .notes-section h3 { font-size: 0.9rem; color: var(--text-secondary); font-weight: 600; margin: 0 0 10px 0; }
        .notes-list { display: flex; overflow-x: auto; padding: 5px 0; scrollbar-width: none; }
        .notes-list::-webkit-scrollbar { display: none; }
        .note-item { display: flex; flex-direction: column; align-items: center; margin-right: 15px; cursor: pointer; flex-shrink: 0; }
        .note-circle { width: 60px; height: 60px; border-radius: 50%; background-color: var(--bg-light); border: 2px solid var(--border-color); display: flex; justify-content: center; align-items: center; margin-bottom: 5px; font-size: 1.5rem; }

        /* Chat List Styles */
        .chat-item { display: flex; align-items: center; padding: 12px 20px; cursor: pointer; position: relative; transition: background-color 0.1s; }
        .chat-item:hover, .chat-item.active { background-color: var(--bg-medium); }
        .chat-avatar { width: 50px; height: 50px; border-radius: 50%; margin-right: 15px; background-color: var(--text-secondary); flex-shrink: 0; }
        .chat-info { flex-grow: 1; min-width: 0; }
        .chat-info strong { display: block; font-size: 0.95rem; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .chat-info span { display: block; font-size: 0.85rem; color: var(--text-secondary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .unread-indicator { position: absolute; right: 20px; width: 8px; height: 8px; background-color: var(--instagram-blue); border-radius: 50%; }

        /* Message Panel */
        .message-panel { 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
            background-color: var(--bg-dark); 
        }
        .default-message-panel { flex-grow: 1; display: flex; justify-content: center; align-items: center; flex-direction: column; text-align: center; padding: 20px; }
        .default-message-panel .messenger-icon { width: 96px; height: 96px; border: 2px solid var(--text-primary); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 48px; margin-bottom: 20px; }
        .default-message-panel h1 { font-size: 1.8rem; font-weight: 300; margin-bottom: 5px; }
        .default-message-panel button { background-color: var(--instagram-blue); color: var(--text-primary); border: none; padding: 8px 15px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.9rem; }
        
        /* Chat Content */
        .chat-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 20px; border-bottom: 1px solid var(--border-color); }
        .chat-area { flex-grow: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; }
        .chat-input-container { padding: 10px 20px; border-top: 1px solid var(--border-color); }
        
        /* Message Bubbles */
        .message-row { display: flex; margin-bottom: 10px; }
        .message-bubble { padding: 8px 16px; border-radius: 18px; max-width: 70%; font-size: 0.95rem; word-break: break-word; }
        .my-message { justify-content: flex-end; }
        .my-message .message-bubble { background: var(--instagram-blue); color: #fff; }
        .friend-message { justify-content: flex-start; }
        .friend-message .message-bubble { background: var(--bubble-friend); color: #f5f5f5; }

        /* User List Styles */
        .user-list { padding: 10px 20px; }
        .user-row { display: flex; align-items: center; margin-bottom: 10px; }
        .user-row img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .user-row strong { display: block; font-size: 0.95rem; font-weight: 600; }
        .user-row span { font-size: 0.85rem; color: var(--text-secondary); }
    </style>
</head>
<body>

    <div class="container">
        <div class="sidebar">
            <div class="sidebar-top">
                <div class="logo-text" style="font-family: 'Billabong', cursive; font-size: 2.5rem; margin-bottom: 20px; color: #fff;">IG</div> 
                
                <div class="sidebar-icon"> <a href="/home/index"><i class="fas fa-home"></i></a> </div>
                <div class="sidebar-icon"><a href="#"><i class="fas fa-search"></i></a></div>
                <div class="sidebar-icon"><a href="#"><i class="far fa-compass"></i></a></div>
                <div class="sidebar-icon"><a href="#"><i class="fas fa-video"></i></a></div>
                <div class="sidebar-icon dm-icon active"><a href="#"><i class="far fa-comment"></i></a></div>
                <div class="sidebar-icon"><a href="#"><i class="far fa-heart"></i></a></div>
                <div class="sidebar-icon"><a href="#"><i class="far fa-plus-square"></i></a></div>
            </div>
            <div class="sidebar-bottom">
                <div class="sidebar-icon">
                    <a href="/profil">
                    <div class="profile-pic-container">
                        <img src="../../public/img/gambar3.jpg" alt="profile">
                    </div>
                    </a>
                </div>
                <div class="sidebar-icon" style="margin-bottom: 0;"><a href="/profil"><i class="fas fa-bars"></i></a></div>
            </div>
        </div>

        <div style="display: flex; flex-grow: 1; height: 100%;">
            <div class="inbox">
                <div class="inbox-header">
                    <h2><?= $username ?></h2> 
                    <span style="font-size: 20px; cursor: pointer;">✏️</span> 
                </div>
                
                <div class="search-box">
                    <input type="text" placeholder="Cari">
                </div>
                
                <div class="notes-section">
                    <h3>Catatan</h3>
                    <div class="notes-list">
                        <div class="note-item">
                            <div class="note-circle" style="border-color: #f77737;">+</div>
                            <span>Catatan Anda</span>
                        </div>
                        <div class="note-item">
                            <div class="note-circle">🎵</div>
                            <span>budi_m...</span>
                        </div>
                        <div class="note-item">
                            <div class="note-circle">💖</div>
                            <span>Loved ones</span>
                        </div>
                        <div class="note-item">
                            <div class="note-circle">☀️</div>
                            <span>Perfect Perspe...</span>
                        </div>
                    </div>
                </div>
                
                <div class="chat-list-section">
                    <div class="chat-list-header">
                        <span><?= $chat_list_title ?></span>
                        <span class="request-link">Permintaan (<?= $request_count ?>)</span>
                    </div>

                    <?php if (!empty($users) && is_array($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <div class="chat-item" data-chat="<?= htmlspecialchars($user['username']) ?>">
                                <div class="chat-avatar" style="background-color: #e95950;">
                                    <?php if (!empty($user['foto'])): ?>
                                        <img src="public/img/<?= htmlspecialchars($user['foto']) ?>" style="width:100%;height:100%;border-radius:50%;">
                                    <?php else: ?>
                                        <i class="fas fa-user"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="chat-info">
                                    <strong><?= htmlspecialchars($user['username']) ?></strong>
                                    <span><?= htmlspecialchars($user['nama_lengkap']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="message-panel">
                <div class="default-message-panel" id="defaultPanel">
                    <div class="messenger-icon">💬</div>
                    <h1>Pesan Anda</h1>
                    <p>Kirim foto dan pesan pribadi ke teman atau grup</p>
                    <button>Kirim Pesan</button>
                </div>
                
                <div class="chat-header" style="display:none;">
                    <div>
                        <strong id="chatPartner"></strong>
                        <span style="color:var(--text-secondary);font-size:0.9rem;" id="chatStatus"></span>
                    </div>
                    <div><i class="fas fa-video" style="margin-right:15px;cursor:pointer;"></i><i class="fas fa-info-circle" style="cursor:pointer;"></i></div>
                </div>
                <div class="chat-area" style="display:none;"></div>
                <div class="chat-input-container" style="display:none;">
                    <form id="chatForm" style="display:flex;gap:8px;">
                        <input type="text" id="chatInput" placeholder="Kirim pesan..." style="flex:1;padding:8px;border-radius:20px;border:none;background:var(--bg-light);color:var(--text-primary);outline:none;">
                        <button type="submit" style="background:var(--instagram-blue);color:#fff;border:none;padding:0 16px;border-radius:20px;font-weight:600;cursor:pointer;">Kirim</button>
                        <button type="button" id="friendSend" style="background:var(--bubble-friend);color:#fff;border:none;padding:0 16px;border-radius:20px;font-weight:600;cursor:pointer;">Teman</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
const chatBtn = document.querySelector('.default-message-panel button');
const chatHeader = document.querySelector('.chat-header');
const chatArea = document.querySelector('.chat-area');
const chatInputContainer = document.querySelector('.chat-input-container');
const defaultPanel = document.getElementById('defaultPanel');
const chatPartnerElement = document.getElementById('chatPartner');
const chatStatusElement = document.getElementById('chatStatus');

// Inisialisasi data pesan (biar pesan tetap ada saat pindah chat)
const chatMessages = {
    "Putri": [{text: "Hai, kamu di mana?", isMine: false}, {text: "Aku di rumah, kenapa?", isMine: true}],
    "sintia": [{text: "Cek DM!", isMine: false}],
    ".._._..": [{text: "Lihat lampiranku ya", isMine: true}]
};

let currentChat = null;

// Tampilkan panel chat saat tombol 'Kirim Pesan' diklik
chatBtn.addEventListener('click', function() {
    // Simulasi membuka chat pertama (Putri)
    openChat('Putri');
});

document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    sendMessage(true); // Pesan dari saya
});

document.getElementById('friendSend').addEventListener('click', function() {
    sendMessage(false); // Pesan dari teman (simulasi)
});

// Fungsi untuk mengirim pesan
function sendMessage(isMine) {
    var input = document.getElementById('chatInput');
    var text = input.value.trim();
    if (!text || !currentChat) return;

    const newMsg = { text: text, isMine: isMine };
    chatMessages[currentChat].push(newMsg);

    // Tampilkan pesan baru
    renderMessage(newMsg);

    input.value = '';
    chatArea.scrollTop = chatArea.scrollHeight;
}

// Fungsi untuk merender satu pesan
function renderMessage(msg) {
    var msgRow = document.createElement('div');
    msgRow.className = 'message-row ' + (msg.isMine ? 'my-message' : 'friend-message');
    var bubble = document.createElement('div');
    bubble.className = 'message-bubble';
    bubble.textContent = msg.text;
    msgRow.appendChild(bubble);
    chatArea.appendChild(msgRow);
}

// Fungsi untuk membuka chat
function openChat(chatName) {
    currentChat = chatName;

    document.querySelectorAll('.chat-item').forEach(item => item.classList.remove('active'));
const chatItem = document.querySelector(`.chat-item[data-chat="${chatName}"]`);
if (chatItem) {
    chatItem.classList.add('active');
}

    // Update header
    chatPartnerElement.textContent = currentChat;
    chatStatusElement.textContent = chatName === 'Putri' ? 'Aktif 1 jam lalu' : 'Aktif 10m lalu'; 
    
    // Tampilkan panel chat
    defaultPanel.style.display = 'none';
    chatHeader.style.display = 'flex';
    chatArea.style.display = 'flex';
    chatInputContainer.style.display = 'block';

    // Render semua pesan
    chatArea.innerHTML = '';
    if (chatMessages[currentChat]) {
        chatMessages[currentChat].forEach(renderMessage);
    } else {
        // Jika chat baru belum ada datanya
        chatMessages[currentChat] = [];
    }

    chatArea.scrollTop = chatArea.scrollHeight;
}

// Pilih chat dari daftar
document.querySelectorAll('.chat-item').forEach(function(item) {
    item.addEventListener('click', function() {
        const chatName = item.getAttribute('data-chat');
        openChat(chatName);
    });
});
</script>
</body>
</html>