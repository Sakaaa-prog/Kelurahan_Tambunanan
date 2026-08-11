<?php
require_once __DIR__ . '/includes/db.php';

function h($val) { return htmlspecialchars((string) $val, ENT_QUOTES); }

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$artikel = null;

try {
    $pdo = get_db();
    $stmt = $pdo->prepare('SELECT * FROM artikel WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $artikel = $stmt->fetch();
} catch (Throwable $e) {
    error_log('artikel-detail.php DB error: ' . $e->getMessage());
}

$page_title = $artikel ? $artikel['judul'] : 'Artikel Tidak Ditemukan';
$page_description = $artikel ? $artikel['ringkasan'] : 'Artikel yang kamu cari tidak ditemukan.';
require __DIR__ . '/includes/partials/nav.php';
?>
<main class="flex-1">
<?php if (!$artikel): ?>
<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface">
<div class="max-w-2xl mx-auto px-margin-mobile text-center">
<h1 class="font-headline-md text-headline-md text-primary mb-4">Artikel Tidak Ditemukan</h1>
<p class="text-on-surface-variant mb-8">Artikel yang kamu cari mungkin sudah dihapus atau linknya salah.</p>
<a class="inline-block bg-primary text-on-primary px-8 py-3 font-label-lg uppercase tracking-widest hover:bg-primary-container transition-all" href="artikel.php">&larr; Kembali ke Artikel</a>
</div>
</section>
<?php else: ?>
<div class="aspect-[21/9] w-full">
<img alt="<?= h($artikel['judul']) ?>" class="w-full h-full object-cover" src="<?= h($artikel['image_path']) ?>"/>
</div>
<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface">
<article class="max-w-3xl mx-auto px-margin-mobile md:px-gutter">
<a class="text-label-md font-label-md text-gold-accent uppercase tracking-widest hover:underline" href="artikel.php">&larr; Kembali ke Artikel</a>
<h1 class="font-headline-lg text-headline-lg text-primary mt-6 mb-4"><?= h($artikel['judul']) ?></h1>
<time class="text-label-md font-label-md text-on-surface-variant/60 block mb-10"><?= h(date('d F Y', strtotime($artikel['tanggal']))) ?> &middot; Ditulis oleh <?= h($artikel['penulis']) ?></time>
<div class="prose-content font-body-lg text-body-lg text-on-surface leading-relaxed space-y-6">
<?php foreach (explode("\n\n", $artikel['konten']) as $paragraf): ?>
<?php if (trim($paragraf) !== ''): ?>
<p><?= nl2br(h($paragraf)) ?></p>
<?php endif; ?>
<?php endforeach; ?>
</div>
</article>
</section>
<?php endif; ?>
</main>
<?php require __DIR__ . '/includes/partials/footer.php'; ?>
