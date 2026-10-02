<?php
$page_title = 'Buat Pesanan';
require_once '../includes/auth.php';
requireLogin();
require_once '../config/database.php';
include '../includes/header.php';

$services = $conn->query("SELECT * FROM services WHERE is_active = 1 ORDER BY name ASC");
$services_list = [];
while ($s = $services->fetch_assoc()) $services_list[] = $s;

$selected_id = intval($_GET['service_id'] ?? 0);

$error = '';
if (isset($_SESSION['order_error'])) { $error = $_SESSION['order_error']; unset($_SESSION['order_error']); }
?>

<div class="wrapper">
    <div class="page-header" style="text-align: center; margin-bottom: 2rem;">
        <h1>📦 Buat Pesanan</h1>
        <p>Isi formulir di bawah untuk membuat pesanan baru</p>
    </div>

    <?php if ($error): ?>
    <div class="alert alert-danger" style="max-width:600px; margin: 0 auto 1.5rem auto;">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div style="max-width:600px; margin: 0 auto;">
        <div class="card">
            <div class="card-header">📝 Detail Pesanan</div>
            <div class="card-body">
                <form method="POST" action="/smartclean/proses/order_proses.php">

                    <div class="form-group">
                        <label class="form-label">Layanan</label>
                        <select name="service_id" class="form-control" id="serviceSelect"
                                required onchange="updatePrice()">
                            <option value="">-- Pilih Layanan --</option>
                            <?php foreach ($services_list as $s): ?>
                            <option value="<?= $s['id'] ?>"
                                data-price="<?= $s['price'] ?>"
                                data-duration="<?= $s['duration_days'] ?>"
                                <?= $selected_id === $s['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name']) ?> — Rp <?= number_format($s['price'], 0, ',', '.') ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah Sepatu (pasang)</label>
                        <input type="number" name="quantity" class="form-control" id="quantityInput"
                               min="1" max="20" value="1" required onchange="updatePrice()">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Antar</label>
                        <input type="date" name="order_date" class="form-control"
                               min="<?= date('Y-m-d') ?>"
                               value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="notes" class="form-control"
                            placeholder="Contoh: Sepatu putih, ada noda tinta di bagian kiri..."></textarea>
                    </div>


                    <style>
                    .pay-card-group {
                        display: flex !important;
                        flex-direction: column !important;
                        gap: .75rem !important;
                        margin-top: 0.5rem;
                    }

                    .pay-card {
                        display: flex !important;
                        align-items: center !important;
                        gap: 1rem !important;
                        padding: 1rem 1.25rem !important;
                        border: 2px solid var(--gray-200) !important;
                        border-radius: var(--radius) !important;
                        cursor: pointer !important;
                        background: var(--white) !important;
                        transition: border-color .2s, box-shadow .2s, background .2s !important;
                        position: relative !important;
                        user-select: none !important;
                    }

                    .pay-card input[type="radio"] {
                        position: absolute !important;
                        opacity: 0 !important;
                        width: 0 !important;
                        height: 0 !important;
                        pointer-events: none !important;
                    }

                    .pay-card:hover {
                        border-color: #93c5fd !important;
                        background: #f8faff !important;
                    }

                    .pay-card:has(input:checked) {
                        border-color: var(--primary) !important;
                        background: linear-gradient(145deg, #eff6ff, #f0f7ff) !important;
                        box-shadow: 0 0 0 4px rgba(37,99,235,.1) !important;
                    }

                    .pay-card-logo {
                        width: 60px !important;
                        height: 42px !important;
                        border-radius: 10px !important;
                        background: var(--gray-100) !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        flex-shrink: 0 !important;
                        overflow: hidden !important;
                        border: 1px solid var(--gray-200) !important;
                    }

                    .pay-card-logo img {
                        width: 100% !important;
                        height: 100% !important;
                        object-fit: contain !important;
                        padding: 5px !important;
                    }

                    .pay-card-info {
                        flex: 1 !important;
                        min-width: 0 !important;
                        text-align: left !important;
                    }

                    .pay-card-name {
                        display: block !important;
                        font-weight: 700 !important;
                        font-size: .95rem !important;
                        color: var(--gray-900) !important;
                    }

                    .pay-card-desc {
                        display: block !important;
                        font-size: .78rem !important;
                        color: var(--gray-400) !important;
                        margin-top: 2px !important;
                    }

                    .pay-card-radio {
                        width: 22px !important;
                        height: 22px !important;
                        border-radius: 50% !important;
                        border: 2px solid var(--gray-200) !important;
                        background: var(--white) !important;
                        flex-shrink: 0 !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        color: transparent !important;
                        transition: all .18s !important;
                    }

                    .pay-card:has(input:checked) .pay-card-radio {
                        background: var(--primary) !important;
                        border-color: var(--primary) !important;
                        color: var(--white) !important;
                        box-shadow: 0 2px 8px rgba(37,99,235,.35) !important;
                    }
                    </style>

                    <div class="form-group">
                        <label class="form-label">Metode Pembayaran</label>

                        <div class="pay-card-group">

                            <label class="pay-card" for="pm-qris">
                                <input type="radio" name="payment_method" value="qris" id="pm-qris" required>
                                <div class="pay-card-logo">
                                    <img src="/smartclean/assets/img/qrisLogo.png" alt="Logo QRIS">
                                </div>
                                <div class="pay-card-info">
                                    <span class="pay-card-name">QRIS</span>
                                    <span class="pay-card-desc">Bayar otomatis via e-Wallet / m-Banking</span>
                                </div>
                                <div class="pay-card-radio">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                            </label>

                            <label class="pay-card" for="pm-cod">
                                <input type="radio" name="payment_method" value="cod" id="pm-cod">
                                <div class="pay-card-logo">
                                    <img src="/smartclean/assets/img/codLogo.png" alt="Logo COD">
                                </div>
                                <div class="pay-card-info">
                                    <span class="pay-card-name">COD (Cash on Delivery)</span>
                                    <span class="pay-card-desc">Bayar tunai langsung saat laundry diantar</span>
                                </div>
                                <div class="pay-card-radio">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                            </label>

                        </div>
                    </div>

                    <style>
                    #qrisOverlay {
                        display: none;
                        position: fixed !important;
                        top: 0 !important;
                        left: 0 !important;
                        width: 100% !important;
                        height: 100% !important;
                        z-index: 999999 !important;
                        background: rgba(15, 23, 42, 0.65) !important;
                        backdrop-filter: blur(5px) !important;
                        align-items: center !important;
                        justify-content: center !important;
                        padding: 1rem !important;
                    }

                    .qris-modal-box {
                        background: #ffffff !important;
                        width: 100% !important;
                        max-width: 400px !important;
                        border-radius: 16px !important;
                        padding: 1.75rem !important;
                        text-align: center !important;
                        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3) !important;
                        position: relative !important;
                        animation: qrisPopCenter 0.2s ease-out !important;
                    }

                    @keyframes qrisPopCenter {
                        from { opacity: 0; transform: scale(0.95); }
                        to { opacity: 1; transform: scale(1); }
                    }

                    .qris-x-close {
                        position: absolute !important;
                        top: 1rem !important;
                        right: 1rem !important;
                        background: #f1f5f9 !important;
                        border: none !important;
                        width: 30px !important;
                        height: 30px !important;
                        border-radius: 50% !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        font-size: 0.85rem !important;
                        color: #64748b !important;
                        cursor: pointer !important;
                        transition: all 0.2s !important;
                    }
                    .qris-x-close:hover {
                        background: #e2e8f0 !important;
                        color: #0f172a !important;
                    }

                    .qris-m-title {
                        font-size: 1.25rem !important;
                        font-weight: 700 !important;
                        color: #0f172a !important;
                        margin-bottom: 0.5rem !important;
                    }

                    .qris-m-instruct {
                        font-size: 0.82rem !important;
                        color: #64748b !important;
                        line-height: 1.4 !important;
                        margin-bottom: 1rem !important;
                        padding: 0 0.25rem !important;
                    }

                    .qris-m-img-frame {
                        width: 100% !important;
                        max-width: 200px !important;
                        margin: 0 auto 1rem auto !important;
                        background: #f8fafc !important;
                        border: 1px solid #e2e8f0 !important;
                        border-radius: 12px !important;
                        padding: 0.75rem !important;
                    }
                    .qris-m-img-frame img {
                        width: 100% !important;
                        height: auto !important;
                        display: block !important;
                    }

                    .qris-m-reminder-box {
                        background: #eff6ff !important;
                        border: 1px solid #bfdbfe !important;
                        color: #1e40af !important;
                        border-radius: 10px !important;
                        padding: 0.75rem 0.85rem !important;
                        font-size: 0.78rem !important;
                        font-weight: 500 !important;
                        margin-bottom: 1.25rem !important;
                        text-align: left !important;
                        line-height: 1.4 !important;
                    }

                    .qris-m-actions {
                        display: flex !important;
                        gap: 0.75rem !important;
                        margin-top: 1rem !important;
                    }

                    .btn-qris-cancel {
                        flex: 1 !important;
                        background: #f1f5f9 !important;
                        color: #64748b !important;
                        border: 1px solid #e2e8f0 !important;
                        padding: 0.65rem 1rem !important;
                        border-radius: 10px !important;
                        font-size: 0.88rem !important;
                        font-weight: 600 !important;
                        cursor: pointer !important;
                        transition: all 0.2s !important;
                    }
                    .btn-qris-cancel:hover {
                        background: #e2e8f0 !important;
                        color: #334155 !important;
                    }

                    .btn-qris-success {
                        flex: 1.3 !important;
                        background: #2563eb !important;
                        color: #ffffff !important;
                        border: none !important;
                        padding: 0.65rem 1rem !important;
                        border-radius: 10px !important;
                        font-size: 0.88rem !important;
                        font-weight: 600 !important;
                        cursor: pointer !important;
                        transition: all 0.2s !important;
                        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2) !important;
                    }
                    .btn-qris-success:hover {
                        background: #1d4ed8 !important;
                        box-shadow: 0 4px 12px -1px rgba(37, 99, 235, 0.3) !important;
                    }
                    </style>

                    <div id="qrisOverlay">
                        <div class="qris-modal-box">
                            <button type="button" class="qris-x-close" onclick="closeQrisModal()" title="Tutup">✕</button>
                            
                            <div class="qris-m-title">E-Wallet / QRIS Pembayaran</div>
                            
                            <p class="qris-m-instruct">
                                Silakan pindai/scan kode QRIS di bawah melalui m-Banking atau aplikasi e-Wallet Anda. Pembayaran Anda akan divalidasi secara manual oleh Admin setelah formulir dikirim.
                            </p>
                            
                            <div class="qris-m-img-frame">
                                <img src="/smartclean/assets/img/qris.png" alt="QRIS SmartClean" onerror="this.style.display='none'; document.getElementById('qrisBoxError').style.display='block';">
                                <div id="qrisBoxError" style="display:none; padding: 1rem; font-size: 0.78rem; color: #ef4444;">
                                    ⚠️ Gambar barcode tidak ditemukan.<br>Simpan file QRIS Anda di folder:<br><code>assets/img/qris.png</code>
                                </div>
                            </div>
                            
                            <div class="qris-m-reminder-box">
                                💡 <strong>Langkah Selanjutnya:</strong><br>
                                Setelah selesai melakukan scan, klik tombol <strong>"✓ Sudah Scan"</strong> untuk menutup pop-up ini, kemudian klik tombol utama <strong>" Buat Pesanan Sekarang"</strong> di bagian bawah form untuk mengirim pesanan Anda ke Admin.
                            </div>
                            
                            <div class="qris-m-actions">
                                <button type="button" class="btn-qris-cancel" onclick="closeQrisModal()">✕ Kembali</button>
                                <button type="button" class="btn-qris-success" onclick="closeQrisModal()">✓ Sudah Scan</button>
                            </div>
                        </div>
                    </div>

                    <div id="pricePreview" style="display:none; margin-top:1.5rem; padding:1rem; background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; font-size:0.9rem; color:#64748b;">
                            <span>Harga per Unit:</span>
                            <span id="pricePerUnit" style="font-weight:600; color:#0f172a;">Rp 0</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; font-size:0.9rem; color:#64748b;">
                            <span>Jumlah:</span>
                            <span id="previewQty" style="font-weight:600; color:#0f172a;">1 pasang</span>
                        </div>
                        <hr style="border:0; border-top:1px dashed #cbd5e1; margin:0.75rem 0;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                            <span style="font-weight:600; color:#0f172a;">Total Pembayaran:</span>
                            <span id="totalPrice" style="font-size:1.25rem; font-weight:800; color:#2563eb;">Rp 0</span>
                        </div>
                        <div id="estimasiText" style="font-size:0.8rem; color:#059669; font-weight:500;"></div>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <button type="submit" class="btn btn-primary" style="width:100%; padding:0.75rem; font-weight:600; font-size:1rem; border-radius:10px;"> Buat Pesanan Sekarang</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
function formatRupiah(n) {
    return 'Rp ' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
function updatePrice() {
    const sel     = document.getElementById('serviceSelect');
    const qty     = parseInt(document.getElementById('quantityInput').value) || 1;
    const opt     = sel.options[sel.selectedIndex];
    const preview = document.getElementById('pricePreview');
    if (sel.value && opt.dataset.price) {
        const price = parseFloat(opt.dataset.price);
        document.getElementById('pricePerUnit').textContent = formatRupiah(price);
        document.getElementById('previewQty').textContent   = qty + ' pasang';
        document.getElementById('totalPrice').textContent   = formatRupiah(price * qty);
        document.getElementById('estimasiText').textContent = '🕐 Estimasi selesai: ' + opt.dataset.duration + ' hari kerja';
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}
window.addEventListener('load', updatePrice);

/* Trigger Pembayaran */
document.querySelectorAll('input[name="payment_method"]').forEach(function(r) {
    r.addEventListener('change', function() {
        if (this.value === 'qris') openQrisModal();
    });
});

function openQrisModal() {
    document.getElementById('qrisOverlay').style.setProperty('display', 'flex', 'important');
    document.body.style.overflow = 'hidden';
}
function closeQrisModal() {
    document.getElementById('qrisOverlay').style.setProperty('display', 'none', 'important');
    document.body.style.overflow = '';
}
document.getElementById('qrisOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeQrisModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeQrisModal();
});
</script>

<?php include '../includes/footer.php'; ?>