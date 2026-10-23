<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $st = mysqli_real_escape_string($koneksi, $_POST['status'] ?? 'pending');
    mysqli_query($koneksi, "UPDATE orders SET status='$st' WHERE id=$id");
    $_SESSION['pesan'] = "Status order berhasil diperbarui.";
    header("Location: " . BASE_URL . "admin/order_detail.php?id=$id"); exit;
}

$judul = "Detail Order - Sudut Temu Cafe";
include '../includes/header.php';
include '../includes/admin_navbar.php';
$active = 'order';

$o = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT o.*, p.nama, p.no_hp FROM orders o JOIN pelanggan p ON o.pelanggan_id = p.id WHERE o.id = $id"));
if (!$o) { header("Location: " . BASE_URL . "admin/order.php"); exit; }
$qd = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id = $id");
?>
<div class="flex min-h-[calc(100vh-4rem)] flex-1">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-4 md:p-8">
        <a href="<?= BASE_URL; ?>admin/order.php" class="inline-flex items-center gap-1.5 text-sm text-charcoal-soft hover:text-primary transition mb-4">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
        </a>
        <div class="mb-6">
            <h1 class="font-display text-3xl text-charcoal">Detail Order</h1>
            <p class="font-mono text-sm text-primary mt-1"><?= htmlspecialchars($o['kode_order']); ?></p>
        </div>

        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                <span><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl border border-secondary/50 shadow-card overflow-hidden">
                    <div class="bg-charcoal px-6 py-3.5">
                        <h2 class="font-display text-base text-white">Informasi Pelanggan</h2>
                    </div>
                    <div class="p-6 space-y-3.5 text-sm">
                        <div><p class="text-charcoal-soft text-xs mb-0.5">Nama</p><p class="font-semibold text-charcoal"><?= htmlspecialchars($o['nama']); ?></p></div>
                        <div><p class="text-charcoal-soft text-xs mb-0.5">No. HP</p><p class="font-semibold text-charcoal"><?= htmlspecialchars($o['no_hp'] ?: '-'); ?></p></div>
                        <div><p class="text-charcoal-soft text-xs mb-0.5">No. Meja</p><p class="font-semibold text-charcoal"><?= htmlspecialchars($o['no_meja']); ?></p></div>
                        <div><p class="text-charcoal-soft text-xs mb-0.5">Waktu</p><p class="font-semibold text-charcoal"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></p></div>
                        <div><p class="text-charcoal-soft text-xs mb-0.5">Catatan</p><p class="font-semibold text-charcoal"><?= htmlspecialchars($o['catatan'] ?: '-'); ?></p></div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-secondary/50 shadow-card overflow-hidden">
                    <div class="bg-charcoal px-6 py-3.5">
                        <h2 class="font-display text-base text-white">Update Status</h2>
                    </div>
                    <form method="POST" class="p-6">
                        <select name="status" class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition mb-3">
                            <option value="pending" <?= $o['status']==='pending'?'selected':''; ?>>Pending</option>
                            <option value="diproses" <?= $o['status']==='diproses'?'selected':''; ?>>Diproses</option>
                            <option value="selesai" <?= $o['status']==='selesai'?'selected':''; ?>>Selesai</option>
                            <option value="dibatalkan" <?= $o['status']==='dibatalkan'?'selected':''; ?>>Dibatalkan</option>
                        </select>
                        <button class="w-full py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition shadow-soft">
                            <i class="fa-solid fa-floppy-disk mr-1.5"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-secondary/50 shadow-card overflow-hidden">
                    <div class="bg-charcoal px-6 py-3.5">
                        <h2 class="font-display text-base text-white">Rincian Item</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-blush text-charcoal-soft text-left">
                                <tr>
                                    <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Menu</th>
                                    <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Harga</th>
                                    <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Jumlah</th>
                                    <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary/30">
                                <?php while ($d = mysqli_fetch_assoc($qd)): ?>
                                <tr>
                                    <td class="px-4 py-3.5 font-medium text-charcoal"><?= htmlspecialchars($d['nama_menu']); ?></td>
                                    <td class="px-4 py-3.5 text-charcoal-soft"><?= rupiah($d['harga']); ?></td>
                                    <td class="px-4 py-3.5 text-charcoal-soft"><?= $d['jumlah']; ?></td>
                                    <td class="px-4 py-3.5 text-right font-semibold text-charcoal"><?= rupiah($d['subtotal']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot class="bg-blush">
                                <tr>
                                    <th colspan="3" class="px-4 py-3.5 text-right text-sm text-charcoal">Total</th>
                                    <th class="px-4 py-3.5 text-right text-lg text-primary"><?= rupiah($o['total_harga']); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>