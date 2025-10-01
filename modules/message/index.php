<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram Clone - Direct</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 300px;
            background-color: #111;
            padding: 10px;
            overflow-y: auto;
        }
        .sidebar h2 {
            font-size: 16px;
            margin: 10px 0;
        }
        .chat-list {
            list-style: none;
            padding: 0;
        }
        .chat-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #333;
            cursor: pointer;
        }
        .chat-item.active {
            background-color: #222;
        }
        .chat-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .chat-item .unread {
            background-color: #0095f6;
            border-radius: 50%;
            width: 8px;
            height: 8px;
            margin-left: 10px;
        }
        .chat-content {
            flex-grow: 1;
        }
        .chat-content p {
            margin: 0;
            font-size: 14px;
        }
        .chat-content .time {
            color: #aaa;
            font-size: 12px;
        }
        .main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }
        .message-box {
            background: url('https://via.placeholder.com/150') no-repeat center;
            width: 100px;
            height: 100px;
            margin-bottom: 10px;
            display: none;
        }
        .message-area {
            display: none;
            width: 70%;
            height: 80%;
            background-color: #111;
            padding: 20px;
            border-radius: 10px;
            overflow-y: auto;
        }
        .message-area.active {
            display: block;
        }
        .message-area p {
            color: #fff;
            margin: 10px 0;
            word-wrap: break-word;
        }
        .input-area {
            width: 70%;
            margin-top: 10px;
            display: none;
        }
        .input-area.active {
            display: flex;
        }
        .input-area input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #333;
            border-radius: 5px 0 0 5px;
            background-color: #222;
            color: #fff;
        }
        .input-area button {
            background-color: #0095f6;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }
        .main p {
            color: #aaa;
            font-size: 14px;
        }
        .default-text {
            display: block;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Catatan</h2>
        <ul class="chat-list">
            <li class="chat-item" data-chat="catatan-anda">
                <img src="https://via.placeholder.com/40" alt="User">
                <div class="chat-content">
                    <p>Catatan Anda <span class="unread"></span></p>
                    <p class="time">13 jam</p>
                </div>
            </li>
            <li class="chat-item" data-chat="salsabila">
                <img src="https://via.placeholder.com/40" alt="User">
                <div class="chat-content">
                    <p>salsabila ardana</p>
                    <p class="time">17 jam</p>
                </div>
            </li>
            <li class="chat-item" data-chat="raauul">
                <img src="https://via.placeholder.com/40" alt="User">
                <div class="chat-content">
                    <p>Raauul</p>
                    <p class="time">13 jam</p>
                </div>
            </li>
        </ul>
        <h2>Pesan Anda</h2>
        <ul class="chat-list">
            <li class="chat-item" data-chat="puuji">
                <img src="https://via.placeholder.com/40" alt="User">
                <div class="chat-content">
                    <p>puuji masama</p>
                    <p class="time">17 jam</p>
                </div>
            </li>
            <li class="chat-item" data-chat="8lowme">
                <img src="https://via.placeholder.com/40" alt="User">
                <div class="chat-content">
                    <p>8lowme</p>
                    <p class="time">2 hari</p>
                </div>
            </li>
        </ul>
    </div>
    <div class="main">
        <div class="message-box"></div>
        <div class="message-area">
            <p>Pesan dari <span id="chat-name"></span>: Halo, apa kabar?</p>
        </div>
        <div class="input-area">
            <input type="text" id="message-input" placeholder="Ketik pesan...">
            <button onclick="sendMessage()">Kirim</button>
        </div>
        <p class="default-text">Kirim foto dan pesan pribadi ke teman atau grup.</p>
    </div>

    <script>
        const chatItems = document.querySelectorAll('.chat-item');
        const messageArea = document.querySelector('.message-area');
        const messageBox = document.querySelector('.message-box');
        const defaultText = document.querySelector('.default-text');
        const inputArea = document.querySelector('.input-area');
        const chatName = document.getElementById('chat-name');
        const messageInput = document.getElementById('message-input');

        chatItems.forEach(item => {
            item.addEventListener('click', () => {
                chatItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                messageArea.classList.add('active');
                messageBox.style.display = 'none';
                defaultText.style.display = 'none';
                inputArea.classList.add('active');
                chatName.textContent = item.querySelector('.chat-content p').textContent;

                console.log(`Opened chat with ${chatName.textContent}`);
            });
        });

        function sendMessage() {
            const name = chatName.textContent;
            const messageText = messageInput.value.trim();
            if (messageText) {
                const message = `${new Date().toLocaleString('id-ID', { timeZone: 'Asia/Jakarta' })} - ${name}: ${messageText}`;
                const p = document.createElement('p');
                p.textContent = message;
                messageArea.appendChild(p);
                messageInput.value = '';
                messageArea.scrollTop = messageArea.scrollHeight;
                console.log(`Message sent to ${name}: ${messageText}`);
            }
        }

        messageInput.addEventListener('keypress', (event) => {
            if (event.key === 'Enter' && messageInput.value.trim()) {
                sendMessage();
            }
        });
    </script>
</body>
</html>