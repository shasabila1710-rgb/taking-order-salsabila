<?php
require_once 'config/koneksi.php';
if (isset($_SESSION['pelanggan_id'])) {
    header("Location: pelanggan/menu.php");
    exit;
}
$judul = "Selamat Datang - Sudut Temu Cafe";
include 'includes/header.php';
?>
<div class="flex-1 relative overflow-hidden">
    <!-- Dekorasi halus -->
    <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-secondary/25 blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] rounded-full bg-accent/15 blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-36 h-36 mb-4">
                    <img src="<?= BASE_URL; ?>assets/logo-brand.png" alt="Sudut Temu Cafe"
                         class="brand-logo w-full h-full object-contain drop-shadow-xl">
                </div>
                <h1 class="font-display text-4xl text-charcoal tracking-tight">Sudut Temu Cafe</h1>
                <p class="text-charcoal-soft mt-2 text-sm leading-relaxed">
                    Temukan sudut nyaman Anda,<br>pesan dengan mudah.
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-soft-lg p-8 border border-secondary/40">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-4 p-3 rounded-lg bg-primary-soft border border-primary/20 text-primary-dark text-sm flex items-start gap-2">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL; ?>proses/simpan_pelanggan.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-1.5">
                            Nama Lengkap
                        </label>
                        <input type="text" name="nama" required
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal placeholder:text-charcoal-soft/60 focus:border-primary focus:ring-2 focus:ring-primary/15 transition"
                            placeholder="Masukkan nama Anda">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-1.5">
                            Nomor Meja
                        </label>
                        <input type="text" name="no_meja" required
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal placeholder:text-charcoal-soft/60 focus:border-primary focus:ring-2 focus:ring-primary/15 transition"
                            placeholder="Contoh: A01">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-1.5">
                            No. HP <span class="text-charcoal-soft font-normal">(opsional)</span>
                        </label>
                        <input type="text" name="no_hp"
                            class="w-full px-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal placeholder:text-charcoal-soft/60 focus:border-primary focus:ring-2 focus:ring-primary/15 transition"
                            placeholder="08xxxxxxxxxx">
                    </div>
                    <button type="submit"
                        class="w-full py-3.5 rounded-xl bg-primary text-white font-semibold hover:bg-primary-dark transition shadow-soft flex items-center justify-center gap-2">
                        Mulai Pesan <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>
            </div>

            <p class="text-center text-sm text-charcoal-soft mt-6">
                Admin? <a href="<?= BASE_URL; ?>admin/login.php" class="text-primary font-semibold hover:underline">Login di sini</a>
            </p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>