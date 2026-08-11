<?php
require_once __DIR__ . '/includes/db.php';

$artikelList = [];
try {
    $pdo = get_db();
    $artikelList = $pdo->query('SELECT id, judul, ringkasan, image_path, penulis, tanggal FROM artikel ORDER BY urutan ASC, id DESC')->fetchAll();
} catch (Throwable $e) {
    error_log('artikel.php DB error: ' . $e->getMessage());
}

function h($val) { return htmlspecialchars((string) $val, ENT_QUOTES); }

$page_title = 'Artikel';
$page_description = 'Kumpulan artikel dan tulisan mendalam seputar Kelurahan Tambunan.';
require __DIR__ . '/includes/partials/nav.php';
?>
<main class="flex-1">
<section class="relative bg-primary text-on-primary py-20 overflow-hidden">
<div class="absolute inset-0 z-0">
<img alt="" class="w-full h-full object-cover opacity-25" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCUHqqp6B4YjN2S5Z6tlBgNcnM1N3wtPoGfJY_jSarR0haB6QSJgiB3TatLgCJqy5u-fMTThVIWpoFJZkt2L4E1jamrfOrXYEVGm3Bo4O3qxV9KARo07fci0nARJuVTzzzUoxY7vlwC_VJT3Kfk2zrUW0AgZHkzMP0sTS8mbI2mlCy27v8RMGsXdSjQRrvqp0kRwDZ3Fvu8AMoPR-hj8S8Ow4YLlHQRv8AQYAhZxOd3d-dyK3aqpdCtGrNiYMP927L7CP-CVNXNOtHa"/>
<div class="absolute inset-0 bg-gradient-to-b from-primary/95 via-primary/90 to-primary"></div>
</div>
<div class="relative z-10 max-w-container-max-width mx-auto px-margin-mobile md:px-gutter text-center">
<span class="text-gold-accent font-label-lg uppercase tracking-widest mb-4 block">Wawasan &amp; Cerita</span>
<h1 class="font-headline-lg text-headline-lg">Artikel</h1>
</div>
</section>

<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface">
<div class="max-w-container-max-width mx-auto px-margin-mobile md:px-gutter">
<?php if (empty($artikelList)): ?>
<p class="text-on-surface-variant text-center">Belum ada artikel yang dipublikasikan.</p>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
<?php foreach ($artikelList as $a): ?>
<a class="group block" href="artikel-detail.php?id=<?= (int) $a['id'] ?>">
<div class="aspect-video mb-6 overflow-hidden">
<img alt="<?= h($a['judul']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" src="<?= h($a['image_path']) ?>"/>
</div>
<time class="text-label-md font-label-md text-on-surface-variant/60 block mb-2"><?= h(date('d F Y', strtotime($a['tanggal']))) ?> &middot; <?= h($a['penulis']) ?></time>
<h2 class="font-headline-sm text-headline-sm text-primary mb-3 group-hover:text-gold-accent transition-colors"><?= h($a['judul']) ?></h2>
<p class="text-on-surface-variant line-clamp-3"><?= h($a['ringkasan']) ?></p>
</a>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</section>
</main>
<?php require __DIR__ . '/includes/partials/footer.php'; ?>
