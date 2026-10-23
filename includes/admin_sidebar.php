<?php $active = $active ?? ''; ?>
<aside class="w-64 bg-white border-r border-secondary/50 hidden md:flex md:flex-col no-print">
    <div class="p-4 flex-1">
        <p class="text-[10px] font-semibold text-charcoal-soft uppercase tracking-[0.15em] px-3 mb-3">Menu Navigasi</p>
        <ul class="space-y-1">
            <li>
                <a href="<?= BASE_URL; ?>admin/dashboard.php"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm
                          <?= $active==='dashboard' ? 'bg-primary text-white font-semibold shadow-soft' : 'text-charcoal-soft font-medium hover:bg-blush hover:text-primary'; ?>">
                    <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL; ?>admin/menu.php"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm
                          <?= $active==='menu' ? 'bg-primary text-white font-semibold shadow-soft' : 'text-charcoal-soft font-medium hover:bg-blush hover:text-primary'; ?>">
                    <i class="fa-solid fa-mug-saucer w-5 text-center"></i>
                    <span>Kelola Menu</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL; ?>admin/order.php"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm
                          <?= $active==='order' ? 'bg-primary text-white font-semibold shadow-soft' : 'text-charcoal-soft font-medium hover:bg-blush hover:text-primary'; ?>">
                    <i class="fa-solid fa-receipt w-5 text-center"></i>
                    <span>Data Order</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL; ?>admin/laporan.php"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm
                          <?= $active==='laporan' ? 'bg-primary text-white font-semibold shadow-soft' : 'text-charcoal-soft font-medium hover:bg-blush hover:text-primary'; ?>">
                    <i class="fa-solid fa-file-lines w-5 text-center"></i>
                    <span>Laporan</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="p-4 border-t border-secondary/50">
        <div class="bg-blush rounded-2xl p-4 text-center border border-secondary/40">
            <img src="<?= BASE_URL; ?>assets/logo-brand.png" alt="Sudut Temu"
                 class="brand-logo h-14 w-auto object-contain mx-auto mb-2">
            <p class="text-[9px] tracking-[0.2em] text-charcoal-soft uppercase">Find Your Cozy Corner</p>
        </div>
    </div>
</aside>