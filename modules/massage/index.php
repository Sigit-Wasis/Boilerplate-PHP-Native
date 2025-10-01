<?php
// index.php - Instagram Direct Messages Clone

// Data dummy untuk stories
$stories = [
    ['id' => 1, 'name' => 'Catatan Anda', 'image' => '👤', 'status' => 'Catatan...'],
    ['id' => 2, 'name' => 'Khifnu', 'image' => '⚡', 'status' => 'Matin', 'muted' => true],
    ['id' => 3, 'name' => 'Perfect Perspe...', 'image' => '💙', 'status' => 'Loved', 'loved' => true],
    ['id' => 4, 'name' => 'bxkd, PXLW', 'image' => '🌿', 'status' => 'paper St']
];

// Data dummy untuk pesan
$messages = [
    ['id' => 1, 'name' => 'Raaul', 'message' => 'Anda: dijapara ul', 'time' => '13 jam', 'avatar' => '👨', 'unread' => false],
    ['id' => 2, 'name' => 'puqii', 'message' => 'masama', 'time' => '17 jam', 'avatar' => '👤', 'unread' => true],
    ['id' => 3, 'name' => '•́⁠ ⁠ ⁠‿⁠ ⁠,⁠•̀', 'message' => 'Anda mengirim lampiran.', 'time' => '2 hari', 'avatar' => '⚪', 'unread' => true],
    ['id' => 4, 'name' => '8lowme', 'message' => '8lowme mengirim lampiran.', 'time' => '2 hari', 'avatar' => '👱', 'unread' => true],
    ['id' => 5, 'name' => 'wijaayakusuma', 'message' => 'Anda mengirim lampiran.', 'time' => '1 minggu', 'avatar' => '🌊', 'unread' => false]
];

$username = '_dwiirhma';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram Clone - Direct Messages</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #000;
            color: #fff;
            overflow: hidden;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 70px;
            background-color: #000;
            border-right: 1px solid #262626;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
        }

        .sidebar-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            cursor: pointer;
            color: #fff;
            font-size: 24px;
            transition: color 0.2s;
        }

        .sidebar-icon:hover {
            color: #888;
        }

        .sidebar-icon.active {
            fill: #fff;
        }

        .notification-badge {
            position: relative;
        }

        .notification-badge::after {
            content: '7';
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ff3040;
            color: #fff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .spacer {
            flex: 1;
        }

        .profile-pic {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #555;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-bottom: 20px;
        }

        /* Messages Panel */
        .messages-panel {
            width: 380px;
            background-color: #000;
            border-right: 1px solid #262626;
            display: flex;
            flex-direction: column;
        }

        .messages-header {
            padding: 20px;
            border-bottom: 1px solid #262626;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .username {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dropdown-icon {
            font-size: 12px;
            color: #888;
        }

        .new-message-icon {
            cursor: pointer;
            font-size: 24px;
        }

        .search-box {
            padding: 12px 16px;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px;
            background-color: #262626;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
        }

        .search-input::placeholder {
            color: #888;
        }

        /* Stories */
        .stories-container {
            display: flex;
            gap: 12px;
            padding: 12px 16px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .stories-container::-webkit-scrollbar {
            display: none;
        }

        .story-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            flex-shrink: 0;
        }

        .story-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: #262626;
            border: 2px solid #555;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            position: relative;
        }

        .story-avatar.muted::after {
            content: '🔇';
            position: absolute;
            bottom: -2px;
            right: -2px;
            font-size: 12px;
            background-color: #000;
            border-radius: 50%;
            padding: 2px;
        }

        .story-name {
            font-size: 12px;
            margin-top: 4px;
            max-width: 60px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid #262626;
        }

        .tab {
            flex: 1;
            padding: 12px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }

        .tab.active {
            border-bottom-color: #fff;
        }

        .tab:not(.active) {
            color: #888;
        }

        /* Message List */
        .message-list {
            flex: 1;
            overflow-y: auto;
        }

        .message-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .message-item:hover {
            background-color: #1a1a1a;
        }

        .message-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #555;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .message-content {
            flex: 1;
            min-width: 0;
        }

        .message-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .message-preview {
            font-size: 14px;
            color: #888;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .unread-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #0095f6;
            flex-shrink: 0;
        }

        /* Chat Area */
        .chat-area {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #000;
        }

        .empty-state {
            text-align: center;
        }

        .empty-icon {
            width: 96px;
            height: 96px;
            border: 2px solid #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 48px;
        }

        .empty-title {
            font-size: 22px;
            font-weight: 300;
            margin-bottom: 8px;
        }

        .empty-description {
            color: #888;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .send-message-btn {
            background-color: #0095f6;
            color: #fff;
            border: none;
            padding: 8px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .send-message-btn:hover {
            background-color: #1877f2;
        }

        .hidden {
            display: none !important;
        }

        .chat-header {
            padding: 16px;
            border-bottom: 1px solid #262626;
            display: flex;
            align-items: center;
        }

        .chat-messages {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-icon">📷</div>
            <div class="sidebar-icon">🏠</div>
            <div class="sidebar-icon">🔍</div>
            <div class="sidebar-icon">🧭</div>
            <div class="sidebar-icon">➕</div>
            <div class="sidebar-icon active">✉️</div>
            <div class="sidebar-icon notification-badge">❤️</div>
            <div class="spacer"></div>
            <div class="profile-pic">👤</div>
            <div class="sidebar-icon">☰</div>
        </div>

        <!-- Messages Panel -->
        <div class="messages-panel">
            <div class="messages-header">
                <div class="username">
                    <?php echo htmlspecialchars($username); ?>
                    <span class="dropdown-icon">▼</span>
                </div>
                <div class="new-message-icon">✏️</div>
            </div>

            <div class="search-box">
                <input type="text" class="search-input" placeholder="Cari" id="searchInput">
            </div>

            <div class="stories-container">
                <?php foreach ($stories as $story): ?>
                <div class="story-item">
                    <div class="story-avatar <?php echo isset($story['muted']) ? 'muted' : ''; ?>">
                        <?php echo $story['image']; ?>
                    </div>
                    <div class="story-name"><?php echo htmlspecialchars($story['name']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="tabs">
                <div class="tab active">Pesan</div>
                <div class="tab">Permintaan</div>
            </div>

            <div class="message-list" id="messageList">
                <?php foreach ($messages as $msg): ?>
                <div class="message-item" data-id="<?php echo $msg['id']; ?>" data-name="<?php echo htmlspecialchars($msg['name']); ?>" data-avatar="<?php echo $msg['avatar']; ?>">
                    <div class="message-avatar"><?php echo $msg['avatar']; ?></div>
                    <div class="message-content">
                        <div class="message-name"><?php echo htmlspecialchars($msg['name']); ?></div>
                        <div class="message-preview"><?php echo htmlspecialchars($msg['message']); ?> · <?php echo $msg['time']; ?></div>
                    </div>
                    <?php if ($msg['unread']): ?>
                    <div class="unread-indicator"></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <div class="empty-state" id="emptyState">
                <div class="empty-icon">✉️</div>
                <div class="empty-title">Pesan Anda</div>
                <div class="empty-description">Kirim foto dan pesan pribadi ke teman atau grup</div>
                <button class="send-message-btn">Kirim pesan</button>
            </div>

            <div class="hidden" id="chatView" style="width: 100%; height: 100%; display: flex; flex-direction: column;">
                <div class="chat-header">
                    <div class="message-avatar" id="chatAvatar"></div>
                    <div class="message-name" id="chatName" style="margin-left: 12px;"></div>
                </div>
                <div class="chat-messages">
                    Pilih pesan untuk melihat percakapan
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const messageItems = document.querySelectorAll('.message-item');
            
            messageItems.forEach(item => {
                const name = item.querySelector('.message-name').textContent.toLowerCase();
                if (name.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Message item click
        document.querySelectorAll('.message-item').forEach(item => {
            item.addEventListener('click', function() {
                const name = this.dataset.name;
                const avatar = this.dataset.avatar;
                
                // Hide empty state, show chat view
                document.getElementById('emptyState').classList.add('hidden');
                document.getElementById('chatView').classList.remove('hidden');
                
                // Update chat header
                document.getElementById('chatAvatar').textContent = avatar;
                document.getElementById('chatName').textContent = name;
                
                // Remove unread indicator
                const unreadIndicator = this.querySelector('.unread-indicator');
                if (unreadIndicator) {
                    unreadIndicator.remove();
                }
            });
        });

        // Tab switching
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>