<?php
require_once __DIR__ . '/includes/db.php';

function h($val) { return htmlspecialchars((string) $val, ENT_QUOTES); }

$infoList = [];
try {
    $pdo = get_db();
    $infoList = $pdo->query('SELECT judul, isi FROM legal_informasi ORDER BY urutan ASC, id ASC')->fetchAll();
} catch (Throwable $e) {
    error_log('legal-informasi.php DB error: ' . $e->getMessage());
}

$page_title = 'Informasi Hukum';
$page_description = 'Informasi hukum praktis untuk wisatawan dan masyarakat Kelurahan Tambunan.';
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
<h1 class="font-headline-lg text-headline-lg mt-4">Informasi Hukum</h1>
</div>
</section>

<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface">
<div class="max-w-3xl mx-auto px-margin-mobile md:px-gutter space-y-6">
<?php if (empty($infoList)): ?>
<p class="text-on-surface-variant text-center">Belum ada informasi yang dipublikasikan.</p>
<?php else: ?>
<?php foreach ($infoList as $info): ?>
<div class="bg-surface-container-lowest luxury-shadow p-8">
<h2 class="font-headline-sm text-headline-sm text-primary mb-3"><?= h($info['judul']) ?></h2>
<p class="text-on-surface-variant leading-relaxed"><?= nl2br(h($info['isi'])) ?></p>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</section>
</main>
<?php require __DIR__ . '/includes/partials/footer.php'; ?>
