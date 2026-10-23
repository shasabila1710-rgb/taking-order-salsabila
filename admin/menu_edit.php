<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$id = (int)($_GET['id'] ?? 0);
$m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $id"));
if (!$m) { header("Location: " . BASE_URL . "admin/menu.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_menu'] ?? '');
    $kat    = mysqli_real_escape_string($koneksi, $_POST['kategori'] ?? '');
    $harga  = (float)($_POST['harga'] ?? 0);
    $desc   = mysqli_real_escape_string($koneksi, $_POST['deskripsi'] ?? '');
    $stok   = (int)($_POST['stok'] ?? 0);
    $status = ($_POST['status'] ?? 'tersedia') === 'habis' ? 'habis' : 'tersedia';

    $gambarSQL = "";
    if (!empty($_FILES['gambar']['name'])) {
        [$ok, $result] = uploadGambar($_FILES['gambar']);
        if (!$ok) { $_SESSION['error'] = $result; }
        else {
            if (!empty($m['gambar']) && file_exists(__DIR__ . '/../assets/uploads/' . $m['gambar'])) {
                @unlink(__DIR__ . '/../assets/uploads/' . $m['gambar']);
            }
            $gambarSQL = ", gambar = '$result'";
        }
    }

    if (!isset($_SESSION['error'])) {
        $sql = "UPDATE menu SET nama_menu='$nama', kategori='$kat', harga=$harga,
                deskripsi='$desc', stok=$stok, status='$status' $gambarSQL WHERE id=$id";
        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['pesan'] = "Menu berhasil diperbarui.";
            header("Location: " . BASE_URL . "admin/menu.php"); exit;
        }
        $_SESSION['error'] = mysqli_error($koneksi);
    }
    $m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $id"));
}

$judul = "Edit Menu - Sudut Temu Cafe";
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
                <h1 class="font-display text-3xl text-charcoal">Edit Menu</h1>
                <p class="text-charcoal-soft mt-1 text-sm">Perbarui detail menu</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 p-4 rounded-xl bg-primary-soft border border-primary/20 text-primary-dark text-sm flex items-start gap-2">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-secondary/50 shadow-card p-6 md:p-8">
                <div class="mb-6 flex items-center gap-4 p-4 bg-blush/60 rounded-xl border border-secondary/40">
                    <div class="w-24 h-24 rounded-xl overflow-hidden bg-white shrink-0 border border-secondary/50">
                        <?php if (!empty($m['gambar']) && file_exists(__DIR__ . '/../assets/uploads/' . $m['gambar'])): ?>
                            <img src="<?= BASE_URL; ?>assets/uploads/<?= htmlspecialchars($m['gambar']); ?>" class="w-full h-full object-cover" id="previewImg">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-secondary text-3xl"><i class="fa-solid fa-image"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-charcoal mb-2">Ganti Gambar (opsional)</label>
                        <input type="file" name="gambar" accept="image/*" id="gambarInput"
                            class="w-full px-4 py-2.5 rounded-xl border border-secondary/60 bg-white text-sm text-charcoal-soft focus:border-primary transition">
                        <p class="text-xs text-charcoal-soft mt-1.5">Format JPG/PNG/WEBP, maks 3 MB</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-charcoal mb-2">Nama Menu</label>
                        <input type="text" name="nama_menu" value="<?= htmlspecialchars($m['nama_menu']); ?>" required
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-2">Kategori</label>
                        <input type="text" name="kategori" value="<?= htmlspecialchars($m['kategori']); ?>" required
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" value="<?= (float)$m['harga']; ?>" min="0" step="100" required
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-2">Stok</label>
                        <input type="number" name="stok" value="<?= (int)$m['stok']; ?>" min="0" required
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
                            <option value="tersedia" <?= $m['status']==='tersedia'?'selected':''; ?>>Tersedia</option>
                            <option value="habis" <?= $m['status']==='habis'?'selected':''; ?>>Habis</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-charcoal mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition resize-none"><?= htmlspecialchars($m['deskripsi']); ?></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6 pt-6 border-t border-secondary/40">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition shadow-soft">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Update Menu
                    </button>
                    <a href="<?= BASE_URL; ?>admin/menu.php" class="px-6 py-2.5 rounded-xl border border-secondary text-charcoal-soft font-semibold text-sm hover:bg-blush transition">Batal</a>
                </div>
            </form>
        </div>
    </main>
</div>
<script>
document.getElementById('gambarInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        let img = document.getElementById('previewImg');
        if (!img) {
            const wrap = e.target.closest('div').querySelector('.w-24.h-24');
            wrap.innerHTML = '<img id="previewImg" class="w-full h-full object-cover">';
            img = document.getElementById('previewImg');
        }
        img.src = ev.target.result;
    };
    reader.readAsDataURL(file);
});
</script>
<?php include '../includes/footer.php'; ?>