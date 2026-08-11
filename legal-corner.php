<?php
$page_title = 'Legal Corner';
$page_description = 'Media informasi dan rujukan hukum untuk wisatawan, pelaku usaha, dan masyarakat Kelurahan Tambunan.';
require __DIR__ . '/includes/partials/nav.php';

$menu = [
    ['icon' => 'gavel', 'title' => 'Informasi Hukum', 'desc' => 'Hak, kewajiban, dan aturan dasar bagi wisatawan & pelaku usaha.', 'link' => 'legal-informasi.php'],
    ['icon' => 'forum', 'title' => 'Tanya Hukum', 'desc' => 'Punya pertanyaan seputar hukum? Kirim lewat form, kami bantu jawab.', 'link' => 'legal-tanya.php'],
    ['icon' => 'account_balance', 'title' => 'Rujukan & Kontak Darurat', 'desc' => 'Daftar instansi, layanan hukum, dan nomor penting yang bisa dihubungi.', 'link' => 'legal-rujukan.php'],
    ['icon' => 'menu_book', 'title' => 'Unduh Buku Saku', 'desc' => 'Dokumen panduan hukum dalam format PDF, gratis diunduh.', 'link' => 'legal-buku.php'],
];
?>
<main class="flex-1">
<section class="relative bg-primary text-on-primary py-20 overflow-hidden">
<div class="absolute inset-0 z-0">
<img alt="" class="w-full h-full object-cover opacity-25" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCUHqqp6B4YjN2S5Z6tlBgNcnM1N3wtPoGfJY_jSarR0haB6QSJgiB3TatLgCJqy5u-fMTThVIWpoFJZkt2L4E1jamrfOrXYEVGm3Bo4O3qxV9KARo07fci0nARJuVTzzzUoxY7vlwC_VJT3Kfk2zrUW0AgZHkzMP0sTS8mbI2mlCy27v8RMGsXdSjQRrvqp0kRwDZ3Fvu8AMoPR-hj8S8Ow4YLlHQRv8AQYAhZxOd3d-dyK3aqpdCtGrNiYMP927L7CP-CVNXNOtHa"/>
<div class="absolute inset-0 bg-gradient-to-b from-primary/95 via-primary/90 to-primary"></div>
</div>
<div class="relative z-10 max-w-container-max-width mx-auto px-margin-mobile md:px-gutter text-center">
<span class="text-gold-accent font-label-lg uppercase tracking-widest mb-4 block">Media Informasi &amp; Rujukan Hukum</span>
<h1 class="font-headline-lg text-headline-lg">Legal Corner</h1>
<p class="max-w-xl mx-auto mt-6 opacity-80">Wisata Nyaman, Hukum Terjaga, Budaya Terhormat.</p>
</div>
</section>

<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface">
<div class="max-w-container-max-width mx-auto px-margin-mobile md:px-gutter">
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
<?php foreach ($menu as $m): ?>
<a class="group bg-surface-container-lowest luxury-shadow p-8 block hover:-translate-y-1 transition-transform duration-300" href="<?= $m['link'] ?>">
<span class="material-symbols-outlined text-gold-accent text-4xl mb-4 block"><?= $m['icon'] ?></span>
<h2 class="font-headline-sm text-headline-sm text-primary mb-2 group-hover:text-gold-accent transition-colors"><?= $m['title'] ?></h2>
<p class="text-on-surface-variant text-sm"><?= $m['desc'] ?></p>
</a>
<?php endforeach; ?>
</div>
</div>
</section>
</main>
<?php require __DIR__ . '/includes/partials/footer.php'; ?>
