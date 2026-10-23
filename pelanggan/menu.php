<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: " . BASE_URL . "index.php"); exit; }
$judul = "Menu - Sudut Temu Cafe";
include '../includes/header.php';
include '../includes/navbar.php';

$kategori_filter = $_GET['kategori'] ?? '';
$where = "WHERE status = 'tersedia'";
if ($kategori_filter !== '') {
    $kf = mysqli_real_escape_string($koneksi, $kategori_filter);
    $where .= " AND kategori = '$kf'";
}
$query = mysqli_query($koneksi, "SELECT * FROM menu $where ORDER BY kategori, nama_menu");
$kategori_list = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM menu ORDER BY kategori");
?>
<main class="flex-1 max-w-7xl mx-auto w-full px-4 md:px-6 py-8">
    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></span>
        </div>
    <?php endif; ?>

    <div class="mb-8">
        <h1 class="font-display text-3xl md:text-4xl text-charcoal tracking-tight">Menu Kami</h1>
        <p class="text-charcoal-soft mt-1.5 text-sm">Pilih menu favorit Anda, dibuat dengan sepenuh hati</p>
    </div>

    <div class="flex flex-wrap gap-2 mb-8">
        <a href="<?= BASE_URL; ?>pelanggan/menu.php"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition border
           <?= $kategori_filter === ''
               ? 'bg-primary text-white border-primary shadow-soft'
               : 'bg-white text-charcoal-soft border-secondary/60 hover:bg-blush hover:text-primary hover:border-primary/40'; ?>">
            Semua
        </a>
        <?php while ($k = mysqli_fetch_assoc($kategori_list)): ?>
            <a href="<?= BASE_URL; ?>pelanggan/menu.php?kategori=<?= urlencode($k['kategori']); ?>"
               class="px-4 py-2 rounded-lg text-sm font-semibold transition border
               <?= $kategori_filter === $k['kategori']
                   ? 'bg-primary text-white border-primary shadow-soft'
                   : 'bg-white text-charcoal-soft border-secondary/60 hover:bg-blush hover:text-primary hover:border-primary/40'; ?>">
                <?= htmlspecialchars($k['kategori']); ?>
            </a>
        <?php endwhile; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php if (mysqli_num_rows($query) === 0): ?>
            <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-secondary/50">
                <i class="fa-solid fa-mug-saucer text-4xl text-secondary mb-3"></i>
                <p class="text-charcoal-soft">Belum ada menu tersedia.</p>
            </div>
        <?php endif; ?>
        <?php while ($m = mysqli_fetch_assoc($query)): ?>
        <div class="group bg-white rounded-2xl border border-secondary/50 shadow-card hover:shadow-soft-lg hover:border-primary/30 transition-all duration-300 overflow-hidden flex flex-col">
            <div class="relative h-44 bg-blush overflow-hidden">
                <?php if (!empty($m['gambar']) && file_exists(__DIR__ . '/../assets/uploads/' . $m['gambar'])): ?>
                    <img src="<?= BASE_URL; ?>assets/uploads/<?= htmlspecialchars($m['gambar']); ?>" alt="<?= htmlspecialchars($m['nama_menu']); ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-secondary">
                        <i class="fa-solid fa-image text-4xl"></i>
                    </div>
                <?php endif; ?>
                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-white/95 backdrop-blur text-[11px] font-semibold text-primary border border-primary/15">
                    <?= htmlspecialchars($m['kategori']); ?>
                </span>
            </div>
            <div class="p-5 flex flex-col flex-1">
                <h3 class="font-display text-lg text-charcoal leading-tight"><?= htmlspecialchars($m['nama_menu']); ?></h3>
                <p class="text-xs text-charcoal-soft mt-1.5 line-clamp-2 flex-1 leading-relaxed"><?= htmlspecialchars($m['deskripsi']); ?></p>
                <div class="flex items-center justify-between mt-4 mb-3">
                    <span class="text-base font-bold text-primary"><?= rupiah($m['harga']); ?></span>
                    <span class="text-[11px] text-charcoal-soft bg-blush px-2 py-0.5 rounded-md border border-secondary/40">Stok <?= (int)$m['stok']; ?></span>
                </div>
                <form action="<?= BASE_URL; ?>proses/tambah_keranjang.php" method="POST" class="flex gap-2">
                    <input type="hidden" name="menu_id" value="<?= $m['id']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" max="<?= (int)$m['stok']; ?>"
                           class="w-16 px-2 py-2 rounded-lg border border-secondary/60 bg-cream text-sm text-center text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
                    <button type="submit"
                        class="flex-1 px-4 py-2 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition shadow-soft">
                        <i class="fa-solid fa-plus mr-1 text-xs"></i> Tambah
                    </button>
                </form>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</main>
<?php include '../includes/footer.php'; ?>