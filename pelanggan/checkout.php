<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: " . BASE_URL . "index.php"); exit; }
$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) { header("Location: " . BASE_URL . "pelanggan/keranjang.php"); exit; }

$total = 0;
$items = [];
foreach ($keranjang as $mid => $jml) {
    $m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $mid"));
    if (!$m) continue;
    $sub = $m['harga'] * $jml;
    $total += $sub;
    $items[] = ['menu' => $m, 'jumlah' => $jml, 'subtotal' => $sub];
}
$judul = "Checkout - Sudut Temu Cafe";
include '../includes/header.php';
?>
<nav class="bg-white border-b border-secondary/50 sticky top-0 z-40 shadow-card no-print">
    <div class="max-w-7xl mx-auto px-4 md:px-6 h-16 flex items-center justify-between">
        <a href="<?= BASE_URL; ?>pelanggan/keranjang.php" class="flex items-center gap-3 text-charcoal hover:text-primary transition group">
            <img src="<?= BASE_URL; ?>assets/logo-brand.png" alt="Sudut Temu" class="brand-logo h-11 w-auto object-contain group-hover:scale-105 transition-transform">
            <span class="hidden sm:inline font-display text-base">Kembali</span>
        </a>
        <span class="text-sm text-charcoal-soft">
            <i class="fa-solid fa-circle-user text-accent mr-1"></i>
            <span class="font-medium text-charcoal"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></span>
        </span>
    </div>
</nav>

<main class="flex-1 max-w-5xl mx-auto w-full px-4 md:px-6 py-8">
    <h1 class="font-display text-3xl md:text-4xl text-charcoal mb-6 tracking-tight">Konfirmasi Pesanan</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl border border-secondary/50 shadow-card p-6">
                <h2 class="font-semibold text-charcoal mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-user text-primary text-sm"></i> Data Pelanggan
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-charcoal-soft text-xs mb-0.5">Nama</p>
                        <p class="font-semibold text-charcoal"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></p>
                    </div>
                    <div>
                        <p class="text-charcoal-soft text-xs mb-0.5">No. Meja</p>
                        <p class="font-semibold text-charcoal"><?= htmlspecialchars($_SESSION['pelanggan_meja']); ?></p>
                    </div>
                </div>
            </div>

            <form action="<?= BASE_URL; ?>proses/simpan_order.php" method="POST">
                <div class="bg-white rounded-2xl border border-secondary/50 shadow-card p-6">
                    <h2 class="font-semibold text-charcoal mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-primary text-sm"></i> Catatan Tambahan
                    </h2>
                    <textarea name="catatan" rows="3"
                        class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal placeholder:text-charcoal-soft/60 focus:border-primary focus:ring-2 focus:ring-primary/15 transition resize-none"
                        placeholder="Contoh: tanpa gula, extra shot, dll (opsional)"></textarea>
                </div>
                <button type="submit"
                    class="w-full mt-5 py-3.5 rounded-xl bg-primary text-white font-bold hover:bg-primary-dark transition shadow-soft flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Pesanan
                </button>
            </form>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-secondary/50 shadow-card overflow-hidden sticky top-24">
                <div class="bg-charcoal px-5 py-3.5">
                    <h2 class="font-display text-base text-white">Rincian Pesanan</h2>
                </div>
                <div class="p-5 space-y-2.5">
                    <?php foreach ($items as $it): ?>
                    <div class="flex justify-between text-sm gap-2">
                        <span class="text-charcoal-soft">
                            <?= htmlspecialchars($it['menu']['nama_menu']); ?>
                            <span class="text-charcoal font-semibold">×<?= $it['jumlah']; ?></span>
                        </span>
                        <span class="font-semibold text-charcoal whitespace-nowrap"><?= rupiah($it['subtotal']); ?></span>
                    </div>
                    <?php endforeach; ?>
                    <hr class="border-secondary/40 my-3">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-charcoal">Total</span>
                        <span class="text-xl font-bold text-primary"><?= rupiah($total); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include '../includes/footer.php'; ?>