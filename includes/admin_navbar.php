<nav class="bg-white border-b border-secondary/50 sticky top-0 z-40 shadow-card no-print">
    <div class="max-w-7xl mx-auto px-4 md:px-6 h-16 flex items-center justify-between">
        <a href="<?= BASE_URL; ?>admin/dashboard.php" class="flex items-center gap-3 group">
            <img src="<?= BASE_URL; ?>assets/logo-brand.png" alt="Sudut Temu Cafe"
                 class="brand-logo h-11 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
            <div class="hidden sm:block leading-tight">
                <p class="font-display text-lg text-charcoal leading-none">Sudut Temu</p>
                <p class="text-[9px] tracking-[0.22em] text-primary uppercase mt-1 font-semibold">Admin Panel</p>
            </div>
        </a>
        <div class="flex items-center gap-3">
            <span class="hidden md:flex items-center gap-2 text-sm text-charcoal-soft">
                <i class="fa-solid fa-circle-user text-accent"></i>
                <span class="font-medium text-charcoal"><?= htmlspecialchars($_SESSION['admin_nama']); ?></span>
            </span>
            <a href="<?= BASE_URL; ?>admin/logout.php"
               class="text-sm px-4 py-2 rounded-lg border border-primary text-primary font-medium hover:bg-primary hover:text-white transition">
                <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
            </a>
        </div>
    </div>
</nav>