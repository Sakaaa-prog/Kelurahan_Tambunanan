<?php
require_once __DIR__ . '/includes/db.php';

function h($val) { return htmlspecialchars((string) $val, ENT_QUOTES); }

$daruratList = [];
$layananList = [];
try {
    $pdo = get_db();
    $daruratList = $pdo->query('SELECT nama, nomor FROM legal_darurat ORDER BY urutan ASC, id ASC')->fetchAll();
    $layananList = $pdo->query('SELECT nama_instansi, deskripsi, kontak FROM legal_layanan ORDER BY urutan ASC, id ASC')->fetchAll();
} catch (Throwable $e) {
    error_log('legal-rujukan.php DB error: ' . $e->getMessage());
}

$page_title = 'Rujukan Layanan & Kontak Darurat';
$page_description = 'Daftar instansi, layanan hukum, dan nomor kontak darurat yang bisa dihubungi masyarakat Kelurahan Tambunan.';
require __DIR__ . '/includes/partials/nav.php';
?>
<main class="flex-1">
<section class="relative bg-primary text-on-primary py-16 overflow-hidden">
<div class="absolute inset-0 z-0">
<img alt="" class="w-full h-full object-cover opacity-25" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCUHqqp6B4YjN2S5Z6tlBgNcnM1N3wtPoGfJY_jSarR0haB6QSJgiB3TatLgCJqy5u-fMTThVIWpoFJZkt2L4E1jamrfOrXYEVGm3Bo4O3qxV9KARo07fci0nARJuVTzzzUoxY7vlwC_VJT3Kfk2zrUW0AgZHkzMP0sTS8mbI2mlCy27v8RMGsXdSjQRrvqp0kRwDZ3Fvu8AMoPR-hj8S8Ow4YLlHQRv8AQYAhZxOd3d-dyK3aqpdCtGrNiYMP927L7CP-CVNXNOtHa"/>
<div class="absolute inset-0 bg-gradient-to-b from-primary/95 via-primary/90 to-primary"></div>
</div>
<div class="relative z-10 max-w-container-max-width mx-auto px-margin-mobile md:px-gutter">
<a class="text-sm opacity-80 hover:opacity-100" href="legal-corner.php">&larr; Legal Corner</a>
<h1 class="font-headline-lg text-headline-lg mt-4">Rujukan Layanan &amp; Kontak Darurat</h1>
</div>
</section>

<!-- Kontak Darurat -->
<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface">
<div class="max-w-3xl mx-auto px-margin-mobile md:px-gutter">
<h2 class="font-headline-sm text-headline-sm text-primary mb-2">Kontak Darurat</h2>
<p class="text-on-surface-variant mb-8 text-sm">Nomor penting, tekan untuk langsung menelepon.</p>
<?php if (empty($daruratList)): ?>
<p class="text-on-surface-variant text-center mb-16">Belum ada data kontak darurat.</p>
<?php else: ?>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-16">
<?php foreach ($daruratList as $d): ?>
<a class="bg-surface-container-lowest luxury-shadow p-6 flex items-center gap-4 hover:bg-error-container/20 transition-all" href="tel:<?= h(preg_replace('/[^0-9+]/', '', $d['nomor'])) ?>">
<span class="material-symbols-outlined text-error text-3xl">emergency</span>
<div>
<h3 class="font-headline-sm text-base text-primary"><?= h($d['nama']) ?></h3>
<p class="text-on-surface-variant text-lg font-bold"><?= h($d['nomor']) ?></p>
</div>
</a>
<?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Rujukan Layanan -->
<h2 class="font-headline-sm text-headline-sm text-primary mb-2">Rujukan Layanan</h2>
<p class="text-on-surface-variant mb-8 text-sm">Instansi &amp; layanan hukum yang bisa dihubungi.</p>
<?php if (empty($layananList)): ?>
<p class="text-on-surface-variant text-center">Belum ada data rujukan layanan.</p>
<?php else: ?>
<div class="space-y-4">
<?php foreach ($layananList as $l): ?>
<div class="bg-surface-container-lowest luxury-shadow p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
<div>
<h3 class="font-headline-sm text-base text-primary mb-1"><?= h($l['nama_instansi']) ?></h3>
<p class="text-on-surface-variant text-sm"><?= h($l['deskripsi']) ?></p>
</div>
<a class="shrink-0 flex items-center gap-2 text-sm text-primary border border-primary/30 px-4 py-2 hover:bg-primary hover:text-on-primary transition-all" href="tel:<?= h(preg_replace('/[^0-9+]/', '', $l['kontak'])) ?>">
<span class="material-symbols-outlined text-base">call</span>
<?= h($l['kontak']) ?>
</a>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</section>
</main>
<?php require __DIR__ . '/includes/partials/footer.php'; ?>
