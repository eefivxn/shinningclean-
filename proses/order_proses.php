<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../user/order.php'); exit;
}

$uid            = $_SESSION['user_id'];
$service_id     = intval($_POST['service_id'] ?? 0);
$quantity       = intval($_POST['quantity'] ?? 0);
$order_date     = trim($_POST['order_date'] ?? '');
$notes          = trim($_POST['notes'] ?? '');
$payment_method = trim($_POST['payment_method'] ?? '');

// Validasi metode pembayaran (whitelist)
$allowed_methods = ['transfer_bank', 'ewallet', 'qris', 'cod'];
if (!in_array($payment_method, $allowed_methods, true)) {
    $_SESSION['order_error'] = 'Silakan pilih metode pembayaran terlebih dahulu.';
    header('Location: ../user/order.php'); exit;
}

// Validasi service
if (!$service_id) {
    $_SESSION['order_error'] = 'Silakan pilih layanan terlebih dahulu.';
    header('Location: ../user/order.php'); exit;
}

// Validasi quantity
if ($quantity < 1 || $quantity > 20) {
    $_SESSION['order_error'] = 'Jumlah sepatu harus antara 1 sampai 20 pasang.';
    header('Location: ../user/order.php'); exit;
}

// Validasi format tanggal
if (empty($order_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $order_date)) {
    $_SESSION['order_error'] = 'Format tanggal tidak valid.';
    header('Location: ../user/order.php'); exit;
}

// Tanggal tidak boleh sebelum hari ini
if ($order_date < date('Y-m-d')) {
    $_SESSION['order_error'] = 'Tanggal antar tidak boleh sebelum hari ini.';
    header('Location: ../user/order.php'); exit;
}

// Tanggal tidak lebih dari 1 tahun ke depan
if ($order_date > date('Y-m-d', strtotime('+1 year'))) {
    $_SESSION['order_error'] = 'Tanggal antar tidak valid.';
    header('Location: ../user/order.php'); exit;
}

// Validasi panjang catatan
if (strlen($notes) > 500) {
    $_SESSION['order_error'] = 'Catatan maksimal 500 karakter.';
    header('Location: ../user/order.php'); exit;
}

// Ambil layanan dari DB
$s = $conn->prepare("SELECT price, duration_days FROM services WHERE id = ? AND is_active = 1");
$s->bind_param("i", $service_id);
$s->execute();
$service = $s->get_result()->fetch_assoc();
$s->close();

if (!$service) {
    $_SESSION['order_error'] = 'Layanan tidak ditemukan atau tidak aktif.';
    header('Location: ../user/order.php'); exit;
}

$total_price = $service['price'] * $quantity;
$pickup_date = date('Y-m-d', strtotime($order_date . ' + ' . $service['duration_days'] . ' days'));

$stmt = $conn->prepare("
    INSERT INTO orders
        (user_id, service_id, quantity, order_date, pickup_date, total_price, notes, payment_method)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("iiissdss",
    $uid, $service_id, $quantity,
    $order_date, $pickup_date, $total_price,
    $notes, $payment_method
);

if ($stmt->execute()) {
    $_SESSION['order_success'] = 'Pesanan berhasil dibuat! Kami akan segera memproses sepatu Anda.';
    header('Location: ../user/history.php');
} else {
    $_SESSION['order_error'] = 'Gagal membuat pesanan. Silakan coba lagi.';
    header('Location: ../user/order.php');
}
$stmt->close();
$conn->close();