<?php
require_once '../config/koneksi.php';
if (isset($_SESSION['admin_id'])) { header("Location: dashboard.php"); exit; }
$judul = "Login Admin - Sudut Temu Cafe";
include '../includes/header.php';
?>
<div class="flex-1 relative overflow-hidden bg-charcoal">
    <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-primary/20 blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] rounded-full bg-accent/15 blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-sm">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-36 h-36 mb-4">
                    <img src="<?= BASE_URL; ?>assets/logo-brand.png" alt="Sudut Temu Cafe"
                         class="brand-logo w-full h-full object-contain drop-shadow-2xl">
                </div>
                <h1 class="font-display text-3xl text-white tracking-tight">Admin Panel</h1>
                <p class="text-secondary/80 text-sm mt-1">Sudut Temu Cafe</p>
            </div>

            <div class="bg-white rounded-2xl shadow-soft-lg p-8 border border-secondary/40">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-4 p-3 rounded-lg bg-primary-soft border border-primary/20 text-primary-dark text-sm flex items-start gap-2">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    </div>
                <?php endif; ?>
                <form action="<?= BASE_URL; ?>admin/proses_login.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-1.5">Username</label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-charcoal-soft/50 text-sm"></i>
                            <input type="text" name="username" required
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal placeholder:text-charcoal-soft/60 focus:border-primary focus:ring-2 focus:ring-primary/15 transition"
                                placeholder="Masukkan username">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-charcoal mb-1.5">Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-charcoal-soft/50 text-sm"></i>
                            <input type="password" name="password" required
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-secondary/60 bg-cream text-charcoal placeholder:text-charcoal-soft/60 focus:border-primary focus:ring-2 focus:ring-primary/15 transition"
                                placeholder="Masukkan password">
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full py-3 rounded-xl bg-primary text-white font-semibold hover:bg-primary-dark transition shadow-soft flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </button>
                </form>
                <p class="text-center text-sm text-charcoal-soft mt-5">
                    <a href="<?= BASE_URL; ?>index.php" class="hover:text-primary transition">
                        <i class="fa-solid fa-arrow-left mr-1"></i>Kembali ke halaman pelanggan
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>