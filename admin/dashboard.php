<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$judul = "Dashboard Admin - Sudut Temu Cafe";

$total_menu    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS j FROM menu"))['j'];
$total_order   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS j FROM orders"))['j'];
$total_pending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS j FROM orders WHERE status='pending'"))['j'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(total_harga),0) AS j FROM orders WHERE status='selesai'"))['j'];
$recent = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o JOIN pelanggan p ON o.pelanggan_id=p.id ORDER BY o.waktu_order DESC LIMIT 5");

include '../includes/header.php';
include '../includes/admin_navbar.php';
$active = 'dashboard';
?>
<div class="flex min-h-[calc(100vh-4rem)] flex-1">
    <?php include '../includes/admin_sidebar.php'; ?>

    <main class="flex-1 p-4 md:p-8">
        <div class="mb-6">
            <h1 class="font-display text-3xl text-charcoal">Dashboard</h1>
            <p class="text-charcoal-soft mt-1 text-sm">Ringkasan aktivitas Sudut Temu Cafe</p>
        </div>

        <!-- Stat cards: konsisten pakai cream/blush + aksen primary, bukan gradient random -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-secondary/50 shadow-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-blush flex items-center justify-center">
                        <i class="fa-solid fa-mug-saucer text-primary"></i>
                    </div>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-charcoal-soft">Menu</span>
                </div>
                <p class="text-3xl font-bold text-charcoal"><?= $total_menu; ?></p>
                <p class="text-xs text-charcoal-soft mt-1">Total menu tersedia</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-secondary/50 shadow-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-blush flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-accent"></i>
                    </div>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-charcoal-soft">Order</span>
                </div>
                <p class="text-3xl font-bold text-charcoal"><?= $total_order; ?></p>
                <p class="text-xs text-charcoal-soft mt-1">Total order masuk</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-primary/30 shadow-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-soft flex items-center justify-center">
                        <i class="fa-solid fa-clock text-primary"></i>
                    </div>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-primary">Pending</span>
                </div>
                <p class="text-3xl font-bold text-charcoal"><?= $total_pending; ?></p>
                <p class="text-xs text-charcoal-soft mt-1">Menunggu diproses</p>
            </div>

            <div class="bg-primary rounded-2xl p-5 border border-primary shadow-soft">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-wallet text-white"></i>
                    </div>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-white/80">Pendapatan</span>
                </div>
                <p class="text-xl font-bold text-white"><?= rupiah($total_pendapatan); ?></p>
                <p class="text-xs text-white/80 mt-1">Order selesai</p>
            </div>
        </div>

        <div class="mt-8 bg-white rounded-2xl border border-secondary/50 shadow-card overflow-hidden">
            <div class="px-6 py-4 border-b border-secondary/40 flex items-center justify-between">
                <h2 class="font-display text-xl text-charcoal">Order Terbaru</h2>
                <a href="<?= BASE_URL; ?>admin/order.php" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1">
                    Lihat semua <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blush text-charcoal-soft text-left">
                        <tr>
                            <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider">Meja</th>
                            <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary/30">
                        <?php if (mysqli_num_rows($recent) === 0): ?>
                            <tr><td colspan="5" class="px-6 py-10 text-center text-charcoal-soft text-sm">Belum ada order.</td></tr>
                        <?php endif; ?>
                        <?php while ($o = mysqli_fetch_assoc($recent)): ?>
                        <tr class="hover:bg-blush/50 transition">
                            <td class="px-6 py-3.5 font-mono text-xs text-charcoal"><?= htmlspecialchars($o['kode_order']); ?></td>
                            <td class="px-6 py-3.5 text-charcoal font-medium"><?= htmlspecialchars($o['nama']); ?></td>
                            <td class="px-6 py-3.5 text-charcoal-soft"><?= htmlspecialchars($o['no_meja']); ?></td>
                            <td class="px-6 py-3.5 font-semibold text-charcoal"><?= rupiah($o['total_harga']); ?></td>
                            <td class="px-6 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= statusBadgeClass($o['status']); ?>">
                                    <?= ucfirst($o['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>