<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id']) || !isset($_SESSION['last_order_id'])) { header("Location: " . BASE_URL . "index.php"); exit; }
$oid = (int)$_SESSION['last_order_id'];
$order = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT o.*, p.nama, p.no_hp FROM orders o JOIN pelanggan p ON o.pelanggan_id = p.id WHERE o.id = $oid"));
if (!$order) { header("Location: " . BASE_URL . "index.php"); exit; }
$detail = [];
$qd = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id = $oid");
while ($d = mysqli_fetch_assoc($qd)) $detail[] = $d;
$judul = "Struk Pesanan - Sudut Temu Cafe";
include '../includes/header.php';
?>
<div class="flex-1 bg-blush/50">
    <main class="max-w-lg mx-auto w-full px-4 py-10">
        <div class="text-center mb-6 no-print">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mb-3 ring-4 ring-emerald-50">
                <i class="fa-solid fa-check text-2xl"></i>
            </div>
            <h1 class="font-display text-3xl text-charcoal">Pesanan Berhasil</h1>
            <p class="text-charcoal-soft text-sm mt-1">Tunjukkan struk ini ke kasir atau pelayan</p>
        </div>

        <div class="bg-white rounded-2xl shadow-soft-lg border border-secondary/50 overflow-hidden">
            <div class="text-center px-6 pt-6 pb-5 border-b-2 border-dashed border-secondary/60">
                <img src="<?= BASE_URL; ?>assets/logo-brand.png" alt="Sudut Temu Cafe"
                     class="brand-logo h-20 w-auto object-contain mx-auto mb-2">
                <h2 class="font-display text-lg text-charcoal">Sudut Temu Cafe</h2>
                <p class="text-[11px] text-charcoal-soft mt-0.5">Jl. Contoh Alamat No. 123, Kota Anda</p>
                <p class="text-[11px] text-charcoal-soft">Telp: 021-1234567</p>
            </div>
            <div class="px-6 py-4 font-mono text-xs space-y-1.5 text-charcoal">
                <div class="flex justify-between"><span class="text-charcoal-soft">Kode Order</span><span class="font-bold"><?= htmlspecialchars($order['kode_order']); ?></span></div>
                <div class="flex justify-between"><span class="text-charcoal-soft">Tanggal</span><span><?= date('d/m/Y H:i', strtotime($order['waktu_order'])); ?></span></div>
                <div class="flex justify-between"><span class="text-charcoal-soft">Nama</span><span><?= htmlspecialchars($order['nama']); ?></span></div>
                <div class="flex justify-between"><span class="text-charcoal-soft">No. Meja</span><span><?= htmlspecialchars($order['no_meja']); ?></span></div>
                <?php if (!empty($order['no_hp'])): ?>
                <div class="flex justify-between"><span class="text-charcoal-soft">No. HP</span><span><?= htmlspecialchars($order['no_hp']); ?></span></div>
                <?php endif; ?>
            </div>
            <div class="border-t-2 border-dashed border-secondary/60 mx-6"></div>
            <div class="px-6 py-4 font-mono text-xs space-y-2.5">
                <?php foreach ($detail as $d): ?>
                <div>
                    <p class="font-semibold text-charcoal"><?= htmlspecialchars($d['nama_menu']); ?></p>
                    <div class="flex justify-between text-charcoal-soft mt-0.5">
                        <span><?= $d['jumlah']; ?> × <?= number_format($d['harga'], 0, ',', '.'); ?></span>
                        <span><?= number_format($d['subtotal'], 0, ',', '.'); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="border-t-2 border-dashed border-secondary/60 mx-6"></div>
            <div class="px-6 py-4 font-mono text-xs">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-charcoal text-sm">TOTAL</span>
                    <span class="font-bold text-primary text-lg"><?= rupiah($order['total_harga']); ?></span>
                </div>
                <div class="flex justify-between mt-2.5">
                    <span class="text-charcoal-soft">Status</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-sans font-semibold <?= statusBadgeClass($order['status']); ?>"><?= ucfirst($order['status']); ?></span>
                </div>
                <?php if (!empty($order['catatan'])): ?>
                <div class="mt-3 pt-3 border-t border-secondary/50">
                    <p class="text-charcoal-soft text-[10px] uppercase tracking-wider mb-1">Catatan</p>
                    <p class="text-charcoal leading-relaxed"><?= nl2br(htmlspecialchars($order['catatan'])); ?></p>
                </div>
                <?php endif; ?>
            </div>
            <div class="px-6 py-4 text-center border-t-2 border-dashed border-secondary/60 bg-blush/40">
                <p class="text-xs text-charcoal-soft">Terima kasih atas kunjungan Anda</p>
                <p class="text-xs text-charcoal-soft">Selamat menikmati</p>
            </div>
        </div>

        <div class="flex gap-3 mt-6 no-print">
            <a href="<?= BASE_URL; ?>pelanggan/menu.php" class="flex-1 text-center px-4 py-2.5 rounded-xl border border-secondary text-charcoal-soft font-semibold text-sm hover:bg-blush transition">
                <i class="fa-solid fa-plus mr-1.5 text-xs"></i> Order Lagi
            </a>
            <a href="<?= BASE_URL; ?>pelanggan/logout_pelanggan.php" class="flex-1 text-center px-4 py-2.5 rounded-xl bg-charcoal text-white font-semibold text-sm hover:bg-charcoal-soft transition">
                <i class="fa-solid fa-right-from-bracket mr-1.5 text-xs"></i> Selesai
            </a>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>