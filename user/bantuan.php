<?php
$page_title = 'Bantuan';
require_once '../includes/auth.php';
requireLogin();
include '../includes/header.php';

$faqs = [
    [
        'q' => 'Bagaimana cara pesan layanan?',
        'a' => 'Klik menu <strong>Pesan</strong> di navigasi atas, pilih layanan yang diinginkan, tentukan jumlah sepatu dan tanggal antar, lalu klik <em>Buat Pesanan</em>. Pesanan Anda akan langsung tercatat dan tim kami segera memprosesnya.',
    ],
    [
        'q' => 'Apakah harga muncul di nota?',
        'a' => 'Ya, total harga dihitung otomatis berdasarkan layanan dan jumlah sepatu. Rincian harga bisa dilihat di halaman <strong>Riwayat</strong> setelah pesanan dibuat.',
    ],
    [
        'q' => 'Bagaimana melihat riwayat transaksi?',
        'a' => 'Buka menu <strong>Riwayat</strong> di navigasi atas. Semua pesanan — dari yang sedang diproses hingga yang sudah selesai — ditampilkan lengkap beserta status terkini.',
    ],
    [
        'q' => 'Apakah bisa bayar di tempat?',
        'a' => 'Tersedia pilihan <em>COD (Cash on Delivery)</em> saat membuat pesanan. Pembayaran dilakukan saat sepatu diantar atau diambil. Kami juga menerima pembayaran QRIS.',
    ],
    [
        'q' => 'Berapa lama proses pencucian sepatu?',
        'a' => 'Estimasi tergantung layanan: Regular Cleaning 2 hari, Deep Clean 3 hari, Premium Clean 4 hari, Repaint 7 hari. Estimasi tampil otomatis saat memilih layanan di halaman Pesan.',
    ],
    [
        'q' => 'Bagaimana cara membatalkan pesanan?',
        'a' => 'Untuk pesanan berstatus <em>Menunggu</em>, hubungi admin via tombol <strong>Chat WhatsApp</strong> di halaman ini agar pesanan dapat segera dibatalkan.',
    ],
];
?>

<style>
/* Inline CSS kritis untuk halaman bantuan — failsafe jika cache belum refresh */
.bantuan-wrap {
    max-width: 1120px;
    margin: 0 auto;
    padding: 2.5rem 1.5rem;
}
.bantuan-title { margin-bottom: 2rem; }
.bantuan-title h1 { font-size: 1.6rem; font-weight: 800; color: #111827; margin: 0 0 .3rem; }
.bantuan-title p  { color: #6b7280; margin: 0; font-size: .95rem; }

.bantuan-grid {
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
}
.bantuan-faq-col { flex: 1 1 auto; min-width: 0; }
.bantuan-side-col { flex: 0 0 270px; width: 270px; }

@media (max-width: 768px) {
    .bantuan-grid { flex-direction: column; }
    .bantuan-side-col { width: 100%; }
}

/* FAQ Card */
.b-faq-item {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    margin-bottom: .75rem;
    overflow: hidden;
    transition: box-shadow .2s;
}
.b-faq-item:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); }

.b-faq-btn {
    width: 100%;
    background: none;
    border: none;
    padding: 1rem 1.2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    cursor: pointer;
    text-align: left;
    font-family: inherit;
}
.b-faq-question {
    font-weight: 700;
    font-size: .95rem;
    color: #1f2937;
    line-height: 1.45;
    flex: 1;
}
.b-faq-icon {
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    color: #64748b;
    transition: background .2s, transform .3s;
}
.b-faq-btn[aria-expanded="true"] .b-faq-icon {
    background: #dbeafe;
    color: #1a73e8;
    transform: rotate(45deg);
}
.b-faq-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height .35s ease;
}
.b-faq-body p {
    padding: 0 1.2rem 1.1rem;
    color: #4b5563;
    font-size: .9rem;
    line-height: 1.7;
    margin: 0;
    border-top: 1px solid #f1f5f9;
    padding-top: .9rem;
}

/* Sidebar card */
.b-side-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    overflow: hidden;
}
.b-side-head {
    padding: 1rem 1.2rem;
    font-weight: 700;
    font-size: 1rem;
    color: #1f2937;
    border-bottom: 1px solid #f1f5f9;
    background: #f8fafc;
}
.b-side-body { padding: 1.2rem; }
.b-side-body p {
    color: #6b7280;
    font-size: .88rem;
    line-height: 1.65;
    margin: 0 0 1.1rem;
}
.b-btn-wa {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    background: #25D366;
    color: #ffffff !important;
    font-weight: 700;
    font-size: .92rem;
    padding: .78rem 1rem;
    border-radius: 10px;
    text-decoration: none !important;
    transition: background .2s, transform .15s;
    box-sizing: border-box;
    margin-bottom: .65rem;
}
.b-btn-wa:hover { background: #1ebe5d; transform: translateY(-1px); }
.b-btn-outline {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    background: transparent;
    color: #374151 !important;
    font-weight: 600;
    font-size: .9rem;
    padding: .72rem 1rem;
    border-radius: 10px;
    border: 1.5px solid #d1d5db;
    text-decoration: none !important;
    transition: border-color .2s, background .2s;
    box-sizing: border-box;
}
.b-btn-outline:hover { border-color: #1a73e8; color: #1a73e8 !important; background: #eff6ff; }
</style>

<div class="bantuan-wrap">

    <div class="bantuan-title">
        <h1>❓ Bantuan</h1>
        <p>Pusat bantuan untuk pengguna Smart Clean</p>
    </div>

    <div class="bantuan-grid">

        <!-- KOLOM KIRI: FAQ -->
        <div class="bantuan-faq-col">
            <?php foreach ($faqs as $i => $item): ?>
            <div class="b-faq-item" id="bfaq-<?= $i ?>">
                <button class="b-faq-btn" onclick="bToggle(<?= $i ?>)" aria-expanded="false">
                    <span class="b-faq-question"><?= htmlspecialchars($item['q']) ?></span>
                    <span class="b-faq-icon">＋</span>
                </button>
                <div class="b-faq-body" id="bfaq-body-<?= $i ?>">
                    <p><?= $item['a'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- KOLOM KANAN: Hubungi Admin -->
        <div class="bantuan-side-col">
            <div class="b-side-card">
                <div class="b-side-head">🤝 Hubungi Admin</div>
                <div class="b-side-body">
                    <p>Butuh bantuan lebih lanjut? Hubungi admin melalui WhatsApp — kami siap membantu setiap hari.</p>

                    <!-- Ganti 6281234567890 dengan nomor WA bisnis Anda -->
                    <a href="https://wa.me/6288805423424?text=Halo%20Shinning%20Clean%2C%20saya%20butuh%20bantuan."
                       target="_blank" rel="noopener noreferrer"
                       class="b-btn-wa">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                             viewBox="0 0 24 24" fill="#ffffff">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Chat WhatsApp
                    </a>

                    <a href="/smartclean/user/dashboard.php" class="b-btn-outline">
                        🏠 Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function bToggle(i) {
    var btn  = document.querySelector('#bfaq-' + i + ' .b-faq-btn');
    var body = document.getElementById('bfaq-body-' + i);
    var open = btn.getAttribute('aria-expanded') === 'true';

    // Tutup semua
    document.querySelectorAll('.b-faq-btn').forEach(function(b) {
        b.setAttribute('aria-expanded', 'false');
        b.querySelector('.b-faq-icon').textContent = '＋';
    });
    document.querySelectorAll('.b-faq-body').forEach(function(b) {
        b.style.maxHeight = null;
    });

    // Buka yang diklik (jika sebelumnya tertutup)
    if (!open) {
        btn.setAttribute('aria-expanded', 'true');
        btn.querySelector('.b-faq-icon').textContent = 'X';
        body.style.maxHeight = body.scrollHeight + 'px';
    }
}
// Buka item pertama otomatis
document.addEventListener('DOMContentLoaded', function () { bToggle(0); });
</script>

<?php include '../includes/footer.php'; ?>