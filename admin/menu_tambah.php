<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_menu'] ?? '');
    $kat    = mysqli_real_escape_string($koneksi, $_POST['kategori'] ?? '');
    $harga  = (float)($_POST['harga'] ?? 0);
    $desc   = mysqli_real_escape_string($koneksi, $_POST['deskripsi'] ?? '');
    $stok   = (int)($_POST['stok'] ?? 0);
    $status = ($_POST['status'] ?? 'tersedia') === 'habis' ? 'habis' : 'tersedia';

    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        [$ok, $result] = uploadGambar($_FILES['gambar']);
        if (!$ok) { $_SESSION['error'] = $result; }
        else { $gambar = $result; }
    }

    if (!isset($_SESSION['error'])) {
        $g = $gambar ? "'$gambar'" : "NULL";
        $sql = "INSERT INTO menu (nama_menu, kategori, harga, deskripsi, stok, gambar, status)
                VALUES ('$nama', '$kat', $harga, '$desc', $stok, $g, '$status')";
        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['pesan'] = "Menu berhasil ditambahkan.";
            header("Location: " . BASE_URL . "admin/menu.php"); exit;
        }
        $_SESSION['error'] = mysqli_error($koneksi);
    }
}

$judul = "Tambah Menu - Sudut Temu Cafe";
include '../includes/header.php';
include '../includes/admin_navbar.php';
$active = 'menu';
?>
<div class="flex min-h-[calc(100vh-4rem)] flex-1">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-4 md:p-8">
        <div class="max-w-3xl">
            <div class="mb-6">
                <a href="<?= BASE_URL; ?>admin/menu.php" class="text-sm text-charcoal-soft hover:text-primary transition inline-flex items-center gap-1 mb-3">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
                </a>
                <h1 class="font-display text-3xl text-charcoal">Tambah Menu</h1>
                <p class="text-charcoal-soft mt-1 text-sm">Isi detail menu baru</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 p-4 rounded-xl bg-primary-soft border border-primary/20 text-primary-dark text-sm flex items-start gap-2">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-secondary/50 shadow-card p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-charcoal mb-2">Nama Menu</label>
                        <input type="text" name="nama_menu" required
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal placeholder:text-charcoal-soft/60 focus:border-primary focus:ring-2 focus:ring-primary/15 transition"
                            placeholder="Contoh: Cafe Latte">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-2">Kategori</label>
                        <input type="text" name="kategori" placeholder="Kopi / Non-Kopi / Makanan / Minuman" required
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal placeholder:text-charcoal-soft/60 focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" min="0" step="100" required
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-2">Stok</label>
                        <input type="number" name="stok" min="0" value="0" required
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
                            <option value="tersedia">Tersedia</option>
                            <option value="habis">Habis</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-charcoal mb-2">Gambar Produk</label>
                        <input type="file" name="gambar" accept="image/*"
                            class="w-full px-4 py-2.5 rounded-xl border border-secondary/60 bg-cream text-sm text-charcoal-soft focus:border-primary transition">
                        <p class="text-xs text-charcoal-soft mt-1.5">Format JPG/PNG/WEBP, maksimal 3 MB</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-charcoal mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal placeholder:text-charcoal-soft/60 focus:border-primary focus:ring-2 focus:ring-primary/15 transition resize-none"
                            placeholder="Deskripsi singkat menu..."></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6 pt-6 border-t border-secondary/40">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition shadow-soft">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Menu
                    </button>
                    <a href="<?= BASE_URL; ?>admin/menu.php" class="px-6 py-2.5 rounded-xl border border-secondary text-charcoal-soft font-semibold text-sm hover:bg-blush transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>