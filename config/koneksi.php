<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base URL sesuai environment Anda
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/taking-order-salsabila/');
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_cafe";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
mysqli_set_charset($koneksi, "utf8mb4");

date_default_timezone_set('Asia/Jakarta');

function rupiah($angka) {
    return "Rp " . number_format((float)$angka, 0, ',', '.');
}

function generateKodeOrder() {
    return "ORD-" . date("YmdHis") . "-" . rand(100, 999);
}

function statusBadgeClass($status) {
    return match($status) {
        'pending'    => 'bg-blush text-primary-dark border border-primary/20',
        'diproses'   => 'bg-primary/10 text-primary border border-primary/30',
        'selesai'    => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'dibatalkan' => 'bg-charcoal/5 text-charcoal border border-charcoal/20',
        default      => 'bg-cream text-charcoal-soft border border-secondary/40',
    };
}

function uploadGambar($file, $targetDir = __DIR__ . '/../assets/uploads/') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return [false, "Tidak ada file yang diupload."];
    }
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        return [false, "Format file harus JPG, JPEG, PNG, atau WEBP."];
    }
    if ($file['size'] > 3 * 1024 * 1024) {
        return [false, "Ukuran file maksimal 3 MB."];
    }
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $namaBaru = 'menu_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target = $targetDir . $namaBaru;
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        return [false, "Gagal menyimpan file."];
    }
    return [true, $namaBaru];
}