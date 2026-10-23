<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: " . BASE_URL . "index.php"); exit; }
$judul = "Keranjang - Sudut Temu Cafe";
include '../includes/header.php';

$keranjang = $_SESSION['keranjang'] ?? [];
$total = 0;
$items = [];
foreach ($keranjang as $mid => $jml) {
    $m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $mid"));
    if (!$m) continue;
    $sub = $m['harga'] * $jml;
    $total += $sub;
    $items[] = ['menu' => $m, 'jumlah' => $jml, 'subtotal' => $sub];
}
?>
<nav class="bg-white border-b border-secondary/50 sticky top-0 z-40 shadow-card no-print">
    <div class="max-w-7xl mx-auto px-4 md:px-6 h-16 flex items-center justify-between">
        <a href="<?= BASE_URL; ?>pelanggan/menu.php" class="flex items-center gap-3 text-charcoal hover:text-primary transition group">
            <img src="<?= BASE_URL; ?>assets/logo-brand.png" alt="Sudut Temu" class="brand-logo h-11 w-auto object-contain group-hover:scale-105 transition-transform">
            <span class="hidden sm:inline font-display text-base">Kembali ke Menu</span>
        </a>
        <span class="text-sm text-charcoal-soft hidden md:inline">
            <i class="fa-solid fa-circle-user text-accent mr-1"></i>
            <span class="font-medium text-charcoal"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></span>
            <span class="text-secondary mx-1">·</span> Meja <?= htmlspecialchars($_SESSION['pelanggan_meja']); ?>
        </span>
    </div>
</nav>

<main class="flex-1 max-w-4xl mx-auto w-full px-4 md:px-6 py-8">
    <h1 class="font-display text-3xl md:text-4xl text-charcoal mb-6 tracking-tight">Keranjang Pesanan</h1>

    <?php if (empty($items)): ?>
        <div class="text-center py-20 bg-white rounded-2xl border border-secondary/50 shadow-card">
            <i class="fa-solid fa-cart-shopping text-4xl text-secondary mb-3"></i>
            <p class="text-charcoal-soft mb-6">Keranjang Anda masih kosong.</p>
            <a href="<?= BASE_URL; ?>pelanggan/menu.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition shadow-soft">
                <i class="fa-solid fa-mug-saucer"></i> Lihat Menu
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-secondary/50 shadow-card overflow-hidden">
            <div class="divide-y divide-secondary/30">
                <?php foreach ($items as $it): $m = $it['menu']; ?>
                <div class="p-4 md:p-5 flex items-center gap-4">
                    <div class="w-16 h-16 rounded-lg bg-blush overflow-hidden shrink-0 border border-secondary/40">
                        <?php if (!empty($m['gambar']) && file_exists(__DIR__ . '/../assets/uploads/' . $m['gambar'])): ?>
                            <img src="<?= BASE_URL; ?>assets/uploads/<?= htmlspecialchars($m['gambar']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-secondary"><i class="fa-solid fa-image"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-charcoal truncate"><?= htmlspecialchars($m['nama_menu']); ?></h3>
                        <p class="text-xs text-charcoal-soft mt-0.5"><?= rupiah($m['harga']); ?> / item</p>
                    </div>
                    <form action="<?= BASE_URL; ?>proses/update_keranjang.php" method="POST" class="flex items-center gap-1">
                        <input type="hidden" name="menu_id" value="<?= $m['id']; ?>">
                        <input type="number" name="jumlah" value="<?= $it['jumlah']; ?>" min="1" max="<?= $m['stok']; ?>"
                               class="w-14 px-2 py-1.5 rounded-lg border border-secondary/60 bg-cream text-sm text-center text-charcoal focus:border-primary transition">
                        <button type="submit" class="p-2 rounded-lg text-primary hover:bg-blush transition" title="Update">
                            <i class="fa-solid fa-rotate text-xs"></i>
                        </button>
                    </form>
                    <div class="text-right w-24">
                        <p class="font-bold text-charcoal"><?= rupiah($it['subtotal']); ?></p>
                    </div>
                    <a href="<?= BASE_URL; ?>proses/hapus_keranjang.php?id=<?= $m['id']; ?>"
                       class="p-2 rounded-lg text-charcoal-soft hover:bg-primary-soft hover:text-primary transition" title="Hapus">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="bg-blush px-4 md:px-5 py-4 flex items-center justify-between border-t border-secondary/40">
                <span class="font-semibold text-charcoal">Total</span>
                <span class="text-xl font-bold text-primary"><?= rupiah($total); ?></span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-6">
            <a href="<?= BASE_URL; ?>pelanggan/menu.php" class="flex-1 text-center px-6 py-3 rounded-xl border border-secondary text-charcoal-soft font-semibold text-sm hover:bg-blush transition">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Lanjut Pesan
            </a>
            <a href="<?= BASE_URL; ?>pelanggan/checkout.php" class="flex-1 text-center px-6 py-3 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition shadow-soft">
                <i class="fa-solid fa-circle-check mr-1.5"></i> Checkout
            </a>
        </div>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>