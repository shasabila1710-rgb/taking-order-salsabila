<?php
$keranjang_count = isset($_SESSION['keranjang']) ? array_sum($_SESSION['keranjang']) : 0;
?>
<nav class="bg-white border-b border-secondary/50 sticky top-0 z-40 shadow-card no-print">
    <div class="max-w-7xl mx-auto px-4 md:px-6 h-16 flex items-center justify-between">
        <a href="<?= BASE_URL; ?>pelanggan/menu.php" class="flex items-center gap-3 group">
            <img src="<?= BASE_URL; ?>assets/logo-brand.png" alt="Sudut Temu Cafe"
                 class="brand-logo h-11 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
            <div class="hidden sm:block leading-tight">
                <p class="font-display text-lg text-charcoal leading-none">Sudut Temu</p>
                <p class="text-[9px] tracking-[0.22em] text-accent uppercase mt-1">Coffee & Eatery</p>
            </div>
        </a>
        <div class="flex items-center gap-2.5">
            <span class="hidden md:flex items-center gap-2 text-sm text-charcoal-soft pr-3 border-r border-secondary/40">
                <i class="fa-solid fa-circle-user text-accent"></i>
                <span class="font-medium text-charcoal"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></span>
                <span class="text-secondary">·</span>
                <span>Meja <?= htmlspecialchars($_SESSION['pelanggan_meja']); ?></span>
            </span>
            <a href="<?= BASE_URL; ?>pelanggan/keranjang.php"
               class="relative px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition shadow-soft">
                <i class="fa-solid fa-cart-shopping mr-1"></i> Keranjang
                <?php if ($keranjang_count > 0): ?>
                    <span class="absolute -top-1.5 -right-1.5 min-w-[22px] h-[22px] px-1.5 rounded-full bg-charcoal text-white text-[11px] font-bold flex items-center justify-center ring-2 ring-white">
                        <?= $keranjang_count; ?>
                    </span>
                <?php endif; ?>
            </a>
            <a href="<?= BASE_URL; ?>pelanggan/logout_pelanggan.php"
               class="p-2.5 rounded-xl border border-secondary text-charcoal-soft hover:bg-blush hover:border-primary hover:text-primary transition"
               title="Selesai & Keluar">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </div>
</nav>