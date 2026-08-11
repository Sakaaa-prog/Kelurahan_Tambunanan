<?php
require_once __DIR__ . '/includes/db.php';

function h($val) { return htmlspecialchars((string) $val, ENT_QUOTES); }

$faqList = [];
try {
    $pdo = get_db();
    $faqList = $pdo->query("SELECT pertanyaan, jawaban FROM legal_faq WHERE status = 'dijawab' AND tampil_publik = 1 ORDER BY id DESC")->fetchAll();
} catch (Throwable $e) {
    error_log('legal-tanya.php DB error: ' . $e->getMessage());
}

$page_title = 'Tanya Hukum';
$page_description = 'Punya pertanyaan seputar hukum? Kirim pertanyaanmu, tim kami akan membantu.';
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
<h1 class="font-headline-lg text-headline-lg mt-4">Tanya Hukum</h1>
</div>
</section>

<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface">
<div class="max-w-3xl mx-auto px-margin-mobile md:px-gutter">

<div class="bg-surface-container-lowest luxury-shadow p-8 mb-16">
<h2 class="font-headline-sm text-headline-sm text-primary mb-2">Kirim Pertanyaan</h2>
<p class="text-on-surface-variant mb-8 text-sm">Isi form di bawah, jawaban akan kami kirim ke email kamu.</p>

<div class="hidden mb-6 px-4 py-3 text-sm" id="form-alert"></div>

<form class="space-y-6" id="tanya-form">
<label class="block">
<span class="font-label-md text-on-surface-variant uppercase block mb-2">Nama</span>
<input class="w-full border border-outline-variant px-4 py-3 focus:outline-none focus:border-primary" name="nama" required type="text"/>
</label>
<label class="block">
<span class="font-label-md text-on-surface-variant uppercase block mb-2">Email</span>
<input class="w-full border border-outline-variant px-4 py-3 focus:outline-none focus:border-primary" name="email" required type="email"/>
</label>
<label class="block">
<span class="font-label-md text-on-surface-variant uppercase block mb-2">Pertanyaan</span>
<textarea class="w-full border border-outline-variant px-4 py-3 focus:outline-none focus:border-primary" name="pertanyaan" required rows="5"></textarea>
</label>
<button class="bg-primary text-on-primary px-8 py-3 font-label-lg uppercase tracking-widest hover:bg-primary-container transition-all disabled:opacity-50" id="tanya-submit" type="submit">
Kirim Pertanyaan
</button>
</form>
</div>

<h2 class="font-headline-sm text-headline-sm text-primary mb-8">Pertanyaan yang Sudah Dijawab</h2>
<?php if (empty($faqList)): ?>
<p class="text-on-surface-variant">Belum ada pertanyaan yang dipublikasikan.</p>
<?php else: ?>
<div class="space-y-4">
<?php foreach ($faqList as $faq): ?>
<details class="bg-surface-container-lowest luxury-shadow p-6 group">
<summary class="font-headline-sm text-base text-primary cursor-pointer list-none flex justify-between items-center gap-4">
<?= h($faq['pertanyaan']) ?>
<span class="material-symbols-outlined text-gold-accent group-open:rotate-180 transition-transform shrink-0">expand_more</span>
</summary>
<p class="text-on-surface-variant mt-4 pt-4 border-t border-outline-variant leading-relaxed"><?= nl2br(h($faq['jawaban'])) ?></p>
</details>
<?php endforeach; ?>
</div>
<?php endif; ?>

</div>
</section>
</main>

<script>
document.getElementById('tanya-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = e.target;
    const alertBox = document.getElementById('form-alert');
    const submitBtn = document.getElementById('tanya-submit');

    alertBox.classList.add('hidden');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Mengirim...';

    try {
        const formData = new FormData(form);
        const res = await fetch('api/legal-tanya-submit.php', { method: 'POST', body: formData });
        const data = await res.json();

        alertBox.textContent = data.message;
        alertBox.className = 'mb-6 px-4 py-3 text-sm ' + (data.success ? 'bg-secondary-container text-on-secondary-container' : 'bg-error-container text-on-error-container');
        alertBox.classList.remove('hidden');

        if (data.success) form.reset();
    } catch (err) {
        alertBox.textContent = 'Tidak dapat terhubung ke server. Coba lagi.';
        alertBox.className = 'mb-6 px-4 py-3 text-sm bg-error-container text-on-error-container';
        alertBox.classList.remove('hidden');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Kirim Pertanyaan';
    }
});
</script>

<?php require __DIR__ . '/includes/partials/footer.php'; ?>
