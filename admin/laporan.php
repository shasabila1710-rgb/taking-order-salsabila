<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$judul = "Laporan Order - Sudut Temu Cafe";
include '../includes/header.php';
include '../includes/admin_navbar.php';
$active = 'laporan';

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

$ta = mysqli_real_escape_string($koneksi, $tgl_awal);
$tk = mysqli_real_escape_string($koneksi, $tgl_akhir);

$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
                             JOIN pelanggan p ON o.pelanggan_id = p.id
                             WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk'
                             ORDER BY o.waktu_order DESC");

$total_pendapatan = 0;
$data = [];
while ($row = mysqli_fetch_assoc($q)) {
    $data[] = $row;
    if ($row['status'] === 'selesai') $total_pendapatan += $row['total_harga'];
}
?>
<div class="flex min-h-[calc(100vh-4rem)] flex-1">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-4 md:p-8">
        <div class="mb-6">
            <h1 class="font-display text-3xl text-charcoal">Laporan Order</h1>
            <p class="text-charcoal-soft mt-1 text-sm">Filter dan cetak laporan pesanan</p>
        </div>

        <form method="GET" class="bg-white rounded-2xl border border-secondary/50 shadow-card p-5 mb-6 grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-sm font-semibold text-charcoal mb-2">Tanggal Awal</label>
                <input type="date" name="tgl_awal" value="<?= htmlspecialchars($tgl_awal); ?>"
                    class="w-full px-4 py-2.5 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
            </div>
            <div>
                <label class="block text-sm font-semibold text-charcoal mb-2">Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir); ?>"
                    class="w-full px-4 py-2.5 rounded-xl border border-secondary/60 bg-cream text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/15 transition">
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition shadow-soft">
                <i class="fa-solid fa-filter mr-1.5"></i> Filter
            </button>
            <a href="<?= BASE_URL; ?>admin/cetak_laporan.php?tgl_awal=<?= $tgl_awal; ?>&tgl_akhir=<?= $tgl_akhir; ?>" target="_blank"
               class="px-5 py-2.5 rounded-xl border border-charcoal text-charcoal font-semibold text-sm hover:bg-charcoal hover:text-white transition text-center">
                <i class="fa-solid fa-print mr-1.5"></i> Cetak
            </a>
        </form>

        <div class="bg-primary text-white rounded-2xl p-6 mb-6 shadow-soft">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-wallet text-xl"></i>
                </div>
                <div>
                    <p class="text-white/80 text-xs uppercase tracking-wider font-medium">Total Pendapatan (Order Selesai)</p>
                    <p class="text-3xl font-bold mt-0.5"><?= rupiah($total_pendapatan); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-secondary/50 shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blush text-charcoal-soft text-left">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Meja</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary/30">
                        <?php if (empty($data)): ?>
                            <tr><td colspan="7" class="px-4 py-10 text-center text-charcoal-soft">Tidak ada data pada periode ini.</td></tr>
                        <?php else: $no=1; foreach ($data as $o): ?>
                            <tr class="hover:bg-blush/50 transition">
                                <td class="px-4 py-3.5 text-charcoal-soft"><?= $no++; ?></td>
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
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>