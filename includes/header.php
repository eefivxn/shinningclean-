<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ?? 'Smart Clean' ?> — Shinning Clean</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/smartclean/assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">
        <img src="/smartclean/assets/img/logo.png"
             alt="Smart Clean"
             class="logo-img"
             style="height:36px;max-height:36px;width:auto;max-width:155px;object-fit:contain;display:block;flex-shrink:0"
             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
        <div class="logo-fallback" style="display:none">
            <div class="logo-icon">👟</div>
            <div>Smart <span>Clean</span></div>
        </div>
    </div>

<style>
/* 1. Mengubah Background Header Menjadi Gelap (Sama dengan Footer) */
header, .header, navbar, .navbar, .main-header {
    background-color: #0f172a !important; /* Warna dasar slate gelap khas footer */
    border-bottom: 1px solid #1e293b !important; /* Garis pembatas bawah tipis */
}

/* 2. Mengubah Warna Teks Menu Utama Menjadi Terang/Abu-abu Lembut */
header a, .header a, navbar a, .navbar a, .main-header a, .nav-link {
    color: #94a3b8 !important; 
    transition: all 0.2s ease-in-out !important;
}

/* 3. Efek Saat Menu Biasa Di-hover (Disorot Mouse) */
header a:hover, .header a:hover, navbar a:hover, .navbar a:hover, .nav-link:hover {
    color: #ffffff !important; /* Menjadi putih bersih */
    background-color: rgba(255, 255, 255, 0.06) !important; /* Efek sorot tipis */
    border-radius: 8px !important;
}

/* 4. Mengubah Kapsul Menu Aktif Menjadi Transparan Translusen (Glassmorphism) */
.nav-links .nav-link.active {
    background: rgba(255, 255, 255, 0.12) !important; /* Efek mika transparan */
    color: #ffffff !important; /* Teks & Icon dipaksa menjadi putih terang */
    box-shadow: none !important; /* Hilangkan shadow bawaan template yang bikin kusam */
    border: 1px solid rgba(255, 255, 255, 0.1) !important; /* Border tipis penguat bentuk */
    border-radius: 8px !important;
}

/* Memastikan teks di dalam menu aktif ikut berwarna putih sempurna */
.nav-links .nav-link.active span {
    color: #ffffff !important;
}

/* ==========================================================================
   5. MODIFIKASI TOMBOL KELUAR (LOGOUT) - HIGH INTIMIDATION CRIMSON
   ========================================================================== */
.nav-links .nav-link.btn-logout {
    color: #ff4d4d !important; /* Merah menyala tajam sejak awal agar langsung mengintimidasi */
    background: rgba(239, 68, 68, 0.05) !important; 
    border: 1px solid rgba(255, 77, 77, 0.4) !important; /* Bingkai merah tegas */
    border-radius: 8px !important;
    padding: 0.5rem 0.85rem !important;
    display: inline-flex !important;
    align-items: center !important;
    font-weight: 600 !important; /* Teks dibuat lebih tebal agar terlihat galak */
    transition: all 0.15s ease-in-out !important; /* Animasi lebih cepat/responsif */
}

/* Efek saat Kursor Mendekat (Hover) - Mode Warning/Danger Flash */
.nav-links .nav-link.btn-logout:hover {
    color: #ffffff !important; /* Teks putih solid */
    background-color: #e11d48 !important; /* Merah crimson pekat/darah yang dominan */
    border-color: #f43f5e !important; /* Bingkai menyala lebih terang */
    
    /* Efek Intimidasi Utama: Glow merah tajam berenergi tinggi */
    box-shadow: 0 0 16px rgba(225, 29, 72, 0.65), 
                0 0 30px rgba(225, 29, 72, 0.3) !important; 
}

/* Efek saat Tombol Klik (Active) - Efek Hentakan Keras */
.nav-links .nav-link.btn-logout:active {
    background-color: #9f1239 !important; /* Merah gelap pekat saat ditekan */
    border-color: #9f1239 !important;
    transform: scale(0.93) !important; /* Efek amblas ke dalam yang lebih ekstrem */
    box-shadow: 0 0 8px rgba(159, 18, 57, 0.8) !important;
}
</style>

    <div class="nav-links">
        <a href="/smartclean/user/dashboard.php"
           class="nav-link <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
           🏠 <span>Beranda</span>
        </a>
        <a href="/smartclean/user/services.php"
           class="nav-link <?= $current_page === 'services.php' ? 'active' : '' ?>">
           🧹 <span>Layanan</span>
        </a>
        <a href="/smartclean/user/order.php"
           class="nav-link <?= $current_page === 'order.php' ? 'active' : '' ?>">
           📦 <span>Pesan</span>
        </a>
        <a href="/smartclean/user/history.php"
           class="nav-link <?= $current_page === 'history.php' ? 'active' : '' ?>">
           📋 <span>Riwayat</span>
        </a>
        <a href="/smartclean/user/bantuan.php"
           class="nav-link <?= $current_page === 'bantuan.php' ? 'active' : '' ?>">
           ❓ <span>Bantuan</span>
        </a>
        <a href="/smartclean/proses/logout.php" class="nav-link btn-logout">
           🚪 <span>Keluar</span>
        </a>
    </div>
</nav>