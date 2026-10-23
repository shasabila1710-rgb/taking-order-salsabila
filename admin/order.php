<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$judul = "Data Order - Sudut Temu Cafe";
include '../includes/header.php';
include '../includes/admin_navbar.php';
$active = 'order';

$filter = $_GET['status'] ?? '';
$where = "";
if ($filter !== '') {
    $f = mysqli_real_escape_string($koneksi, $filter);
    $where = "WHERE o.status = '$f'";
}
$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o JOIN pelanggan p ON o.pelanggan_id = p.id $where ORDER BY o.waktu_order DESC");

$statuses = [
    ''           => ['label' => 'Semua',      'icon' => 'fa-list'],
    'pending'    => ['label' => 'Pending',    'icon' => 'fa-clock'],
    'diproses'   => ['label' => 'Diproses',   'icon' => 'fa-spinner'],
    'selesai'    => ['label' => 'Selesai',    'icon' => 'fa-circle-check'],
    'dibatalkan' => ['label' => 'Dibatalkan', 'icon' => 'fa-circle-xmark'],
];
?>
<div class="flex min-h-[calc(100vh-4rem)] flex-1">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-4 md:p-8">
        <div class="mb-6">
            <h1 class="font-display text-3xl text-charcoal">Data Order</h1>
            <p class="text-charcoal-soft mt-1 text-sm">Kelola semua pesanan pelanggan</p>
        </div>

        <div class="flex flex-wrap gap-2 mb-6">
            <?php foreach ($statuses as $key => $s): ?>
                <a href="<?= BASE_URL; ?>admin/order.php<?= $key ? '?status='.$key : ''; ?>"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition inline-flex items-center gap-1.5 border
                   <?= $filter === $key
                       ? 'bg-primary text-white border-primary shadow-soft'
                       : 'bg-white text-charcoal-soft border-secondary/60 hover:bg-blush hover:text-primary hover:border-primary/40'; ?>">
                    <i class="fa-solid <?= $s['icon']; ?> text-xs"></i> <?= $s['label']; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="bg-white rounded-2xl border border-secondary/50 shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blush text-charcoal-soft text-left">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Meja</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Waktu</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary/30">
                        <?php while ($o = mysqli_fetch_assoc($q)): ?>
                        <tr class="hover:bg-blush/50 transition">
                            <td class="px-4 py-3.5 font-mono text-xs text-charcoal"><?= htmlspecialchars($o['kode_order']); ?></td>
                            <td class="px-4 py-3.5 text-charcoal font-medium"><?= htmlspecialchars($o['nama']); ?></td>
                            <td class="px-4 py-3.5 text-charcoal-soft"><?= htmlspecialchars($o['no_meja']); ?></td>
                            <td class="px-4 py-3.5 font-semibold text-charcoal"><?= rupiah($o['total_harga']); ?></td>
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= statusBadgeClass($o['status']); ?>">
                                    <?= ucfirst($o['status']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-charcoal-soft text-xs"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                            <td class="px-4 py-3.5 text-center">
                                <a href="<?= BASE_URL; ?>admin/order_detail.php?id=<?= $o['id']; ?>"
                                   class="inline-flex p-2 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($q) === 0): ?>
                        <tr><td colspan="7" class="px-4 py-10 text-center text-charcoal-soft">Belum ada order.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>