<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$judul = "Kelola Menu - Sudut Temu Cafe";
include '../includes/header.php';
include '../includes/admin_navbar.php';
$active = 'menu';
$q = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY kategori, nama_menu");
?>
<div class="flex min-h-[calc(100vh-4rem)] flex-1">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-4 md:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="font-display text-3xl text-charcoal">Kelola Menu</h1>
                <p class="text-charcoal-soft mt-1 text-sm">Tambah, edit, dan hapus menu cafe</p>
            </div>
            <a href="<?= BASE_URL; ?>admin/menu_tambah.php"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition shadow-soft">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Menu
            </a>
        </div>

        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                <span><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl border border-secondary/50 shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blush text-charcoal-soft text-left">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Gambar</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Harga</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Stok</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary/30">
                        <?php while ($m = mysqli_fetch_assoc($q)): ?>
                        <tr class="hover:bg-blush/50 transition">
                            <td class="px-4 py-3">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-blush border border-secondary/50">
                                    <?php if (!empty($m['gambar']) && file_exists(__DIR__ . '/../assets/uploads/' . $m['gambar'])): ?>
                                        <img src="<?= BASE_URL; ?>assets/uploads/<?= htmlspecialchars($m['gambar']); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-secondary"><i class="fa-solid fa-image"></i></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-semibold text-charcoal"><?= htmlspecialchars($m['nama_menu']); ?></td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full bg-blush text-charcoal-soft text-xs font-semibold border border-secondary/50">
                                    <?= htmlspecialchars($m['kategori']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-charcoal"><?= rupiah($m['harga']); ?></td>
                            <td class="px-4 py-3 text-charcoal-soft"><?= (int)$m['stok']; ?></td>
                            <td class="px-4 py-3">
                                <?php if ($m['status'] === 'tersedia'): ?>
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">Tersedia</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full bg-charcoal/5 text-charcoal text-xs font-semibold border border-charcoal/20">Habis</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= BASE_URL; ?>admin/menu_edit.php?id=<?= $m['id']; ?>"
                                       class="p-2 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition" title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <a href="<?= BASE_URL; ?>admin/menu_hapus.php?id=<?= $m['id']; ?>"
                                       onclick="return confirm('Hapus menu ini?')"
                                       class="p-2 rounded-lg bg-charcoal/5 text-charcoal-soft hover:bg-red-500 hover:text-white transition" title="Hapus">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($q) === 0): ?>
                        <tr><td colspan="7" class="px-4 py-10 text-center text-charcoal-soft">Belum ada menu.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>