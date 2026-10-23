<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = mysqli_real_escape_string($koneksi, $tgl_awal);
$tk = mysqli_real_escape_string($koneksi, $tgl_akhir);

$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
                             JOIN pelanggan p ON o.pelanggan_id = p.id
                             WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk'
                             ORDER BY o.waktu_order ASC");

$data = [];
$total_pendapatan = 0;
while ($row = mysqli_fetch_assoc($q)) {
    $data[] = $row;
    if ($row['status'] === 'selesai') $total_pendapatan += $row['total_harga'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Laporan - Sudut Temu Cafe</title>
<style>
    @page { size: A4; margin: 15mm; }
    * { box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; color: #3D2B2E; font-size: 12px; margin: 0; padding: 0; }
    .header { display: flex; align-items: center; gap: 16px; border-bottom: 3px double #C85274; padding-bottom: 14px; margin-bottom: 22px; }
    .header img { height: 64px; width: auto; object-fit: contain; }
    .header-text h1 { margin: 0; font-size: 20px; letter-spacing: 2px; color: #C85274; }
    .header-text p { margin: 2px 0; color: #5A4548; font-size: 11px; }
    .info { margin-bottom: 16px; font-size: 12px; }
    .info table { border: none; }
    .info td { padding: 2px 6px; }
    table.data { width: 100%; border-collapse: collapse; font-size: 11px; }
    table.data th, table.data td { border: 1px solid #3D2B2E; padding: 6px 8px; }
    table.data th { background: #FDEEF2; text-align: center; color: #3D2B2E; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .total-box { margin-top: 18px; text-align: right; font-size: 14px; font-weight: bold; color: #C85274; }
    .ttd { margin-top: 50px; text-align: right; font-size: 12px; }
    .ttd .nama { margin-top: 70px; border-top: 1px solid #3D2B2E; display: inline-block; padding-top: 3px; min-width: 180px; text-align: center; }
</style>
</head>
<body>
<div class="header">
    <img src="<?= BASE_URL; ?>assets/logo-brand.png" alt="Sudut Temu Cafe">
    <div class="header-text">
        <h1>SUDUT TEMU CAFE</h1>
        <p>Jl. Contoh Alamat No. 123, Kota Anda</p>
        <p>Telp: 021-1234567 | Email: info@suduttemu.com</p>
    </div>
</div>

<h3 class="text-center" style="margin: 0 0 15px;">LAPORAN ORDER</h3>

<div class="info">
    <table>
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td><?= date('d/m/Y', strtotime($tgl_awal)); ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)); ?></td>
        </tr>
        <tr>
            <td>Tanggal Cetak</td>
            <td>:</td>
            <td><?= date('d/m/Y H:i'); ?></td>
        </tr>
    </table>
</div>

<table class="data">
    <thead>
        <tr>
            <th style="width:30px;">No</th>
            <th style="width:140px;">Kode Order</th>
            <th>Nama Pelanggan</th>
            <th style="width:50px;">Meja</th>
            <th style="width:100px;">Total</th>
            <th style="width:80px;">Status</th>
            <th style="width:110px;">Waktu</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="7" class="text-center">Tidak ada data pada periode ini.</td></tr>
        <?php else: $no=1; foreach ($data as $o): ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td><?= htmlspecialchars($o['kode_order']); ?></td>
                <td><?= htmlspecialchars($o['nama']); ?></td>
                <td class="text-center"><?= htmlspecialchars($o['no_meja']); ?></td>
                <td class="text-right"><?= rupiah($o['total_harga']); ?></td>
                <td class="text-center"><?= ucfirst($o['status']); ?></td>
                <td class="text-center"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
            </tr>
        <?php endforeach; endif; ?>
    </tbody>
</table>

<div class="total-box">
    Total Pendapatan (Order Selesai): <?= rupiah($total_pendapatan); ?>
</div>

<div class="ttd">
    <p>Kota Anda, <?= date('d/m/Y'); ?></p>
    <p>Administrator,</p>
    <p class="nama"><?= htmlspecialchars($_SESSION['admin_nama']); ?></p>
</div>

<script>
    window.onload = () => window.print();
    window.onafterprint = () => window.close();
</script>
</body>
</html>