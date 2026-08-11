<?php
require_once __DIR__ . '/includes/db.php';

// Nilai default (fallback) kalau database belum tersedia/belum di-setup
$stats = [
    'penduduk' => 2450, 'kepala_keluarga' => 612, 'luas_wilayah' => 15.2,
    'jumlah_dusun' => 8, 'ketinggian' => 1200,
];
$lurah = ['nama' => 'Johanis Pabuka, SE', 'no_hp' => '081247412255'];
$lingkunganList = [];

$heroImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuBMOjv0IfwF5C2kz2Rs9Q6mnbU_9mAOmaL2sGGOajBDdq7eAgNI9VIzC8KpwL0MuY8PIJLHLyi6VQsyfZwpsxUfiP5fvTpbCobbUeBtRX0zVF0JqTRNx_LktjxdpyuvdWVMYTCpkfKskcGmnbI_xsoUPPJG_K7Y2U4K5sT3mMon-VkvCyNKytPOA_hI1RZoTzFkub4HUTKYEVfSc9c5qYWySRnTXz4n1ZD_t0x27pH_P304VTC64Md0TzR7E1j7DM6FP-3flLoiEx72';
$profilImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuBVgUM9WtmRnipfEvZlsuHfp-f9yhhI4jt52fMb0EE1mfl0Vu6F2HW0IZhlWCKgGFe5BZN63SegGUQSuQ3POJXd866W8oNXRR8oIP-8qwAVbWUifN1AjlKtgIYpXtu_X8odZVEYirHLnNw5uMe9tIWHjRs-HUHfs69GfE4ygS8jfOk6e7L3EGIT-sJjzm5kezn_uJr_pbHewYR7eKoRUsaJDAH7bDK_tXFV2iYBJwP5iR-cShffL0cUL8fbcvMPmgGruSdaOmamEOOc';
$potensiList = [];
$galeriList = [];
$fasilitasList = [];
$beritaList = [];

try {
    $pdo = get_db();

    $row = $pdo->query('SELECT penduduk, kepala_keluarga, luas_wilayah, jumlah_dusun, ketinggian FROM village_stats WHERE id = 1')->fetch();
    if ($row) $stats = $row;

    $row = $pdo->query('SELECT nama, no_hp FROM lurah WHERE id = 1')->fetch();
    if ($row) $lurah = $row;

    $lingkunganList = $pdo->query('SELECT id, nama, kepala_nama, kepala_hp FROM lingkungan ORDER BY urutan ASC, id ASC')->fetchAll();
    $rtStmt = $pdo->prepare('SELECT nama, ketua_nama, ketua_hp FROM rt WHERE lingkungan_id = :id ORDER BY urutan ASC, id ASC');
    foreach ($lingkunganList as &$l) {
        $rtStmt->execute(['id' => $l['id']]);
        $l['rt'] = $rtStmt->fetchAll();
    }
    unset($l);

    $heroRow = $pdo->query('SELECT image_path FROM hero WHERE id = 1')->fetch();
    if ($heroRow) $heroImage = $heroRow['image_path'];

    $profilRow = $pdo->query('SELECT image_path FROM profil WHERE id = 1')->fetch();
    if ($profilRow) $profilImage = $profilRow['image_path'];

    $potensiList = $pdo->query('SELECT id, judul, deskripsi, image_path FROM potensi ORDER BY urutan ASC, id ASC')->fetchAll();

    $galeriList = $pdo->query('SELECT id, image_path, alt_text, layout FROM galeri ORDER BY urutan ASC, id ASC')->fetchAll();

    $fasilitasList = $pdo->query('SELECT id, nama, gmaps_link FROM fasilitas_umum ORDER BY urutan ASC, id ASC')->fetchAll();

    $beritaList = $pdo->query('SELECT id, kategori, judul, deskripsi, image_path, tanggal FROM berita ORDER BY urutan ASC, id ASC')->fetchAll();
} catch (Throwable $e) {
    // Database belum siap -> tampilkan dengan data default di atas, situs tetap jalan
    error_log('index.php DB fallback: ' . $e->getMessage());

    $potensiList = [
        ['judul' => 'Potensi Alam', 'deskripsi' => 'Keindahan lanskap pegunungan dan sumber mata air alami yang terjaga kelestariannya.', 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAQEg407Klw3Z3R1nDFJp9b_CsOvgrmm6JMkgVCVhTOAT-QbI7VSO6xcATRGtri2dDn-dDfQQeeUXJ62L-h9aWrl2LJOrTV62kuBicCyKUM-X-RHND4jm_e7VePmJ_cnc76INXUSkOGZNypN2bQD2WNeWmnfKSulqZnZwIZKWPnGfDDiOeTyK4Cv_hf4ZBGNbEtgg70M3BQSkeDX9NacHM-pRiClVZA2MVkAUtatiOpVpJWJH0ho7KgOe39hqmhe6h0NY-PnLnBuKn_'],
        ['judul' => 'Warisan Budaya', 'deskripsi' => 'Arsitektur Tongkonan yang megah dan tradisi luhur yang terus diwariskan antar generasi.', 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCUHqqp6B4YjN2S5Z6tlBgNcnM1N3wtPoGfJY_jSarR0haB6QSJgiB3TatLgCJqy5u-fMTThVIWpoFJZkt2L4E1jamrfOrXYEVGm3Bo4O3qxV9KARo07fci0nARJuVTzzzUoxY7vlwC_VJT3Kfk2zrUW0AgZHkzMP0sTS8mbI2mlCy27v8RMGsXdSjQRrvqp0kRwDZ3Fvu8AMoPR-hj8S8Ow4YLlHQRv8AQYAhZxOd3d-dyK3aqpdCtGrNiYMP927L7CP-CVNXNOtHa'],
        ['judul' => 'UMKM Lokal', 'deskripsi' => 'Produk kerajinan tangan dan industri kreatif masyarakat yang mendukung ekonomi desa.', 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCWxl-C0zqg-q8jMC2tgfz9PXcmL0XRMuBabT22RqfQTCLjTTZkOZPJsPnQ_rtuy5uwyRbYUnOK9tdfAObH0iskMbS7dpOJO37Vi-tQsUjCpRfPBLMmgoNCi8bNfm6YCNGT1lq0BeZaWFtg9Wg6h1WIR7o9BWTr_dCXJ2W0MNnXXoNvOlTYu6VnHulqcTXlLM4OpE3z8xn8uT1U2DIYMe8jeGlyMLtzWWHa7hRJYnVGQD1Wk3rdRCbaene_wF9KlwlQ8O2KGiizmxmE'],
        ['judul' => 'Hasil Bumi', 'deskripsi' => 'Kopi Toraja berkualitas premium dan hasil pertanian organik dari tanah subur Tambunan.', 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCBBW4M1-7xcIJdlSO8BCBd5_JPavMTyjXqGn44hlBZFHVleM38fkrwXY272u6mmQPJ9W0HCiKs9HRWwB-3HnBIkgGFLD5EDqj98g5rb96D_Wcn6PlP69j_dPr8ueZi5tO4HbgwQrOAaY_zt5NeqoBU2_3rWQ6XpdEtsjpXvVwLp8Dc04XhNvgGS74hUNNbV2V-EWtHS0jlu80fXn6HJ2-AvEWxoYQEOE8QZQARFVQLtSbIR7GuAtyN33bjaIcqUI3Jg4Ea49PoIPqQ'],
    ];

    $galeriList = [
        ['image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCy0VWq_36siHKJrXOb4CzC6BkIGOWabRI-S3yLiodzogNk1wcy9iDhhEmX3UA18M0QcrWz4ZQxsmaT22hGhDnh1cbOgI3g9eAKKHopq8GEL2yDiW15Ep80b3mX3y9evMr63GBwnTcdS21OpocGCS9uq6-Y0b-uNWY-UTNsbQHWNdQ7R2GLbe3GssTFmK6vTYaCTl1w3uNpu__b1wljtRG1YMGSgGHSMbfeJhWpsC1bVGgjoAOv9AatZdhAVSadv5B-ThsEyqYNNypo', 'alt_text' => 'Tongkonan menjulang di antara kabut pegunungan', 'layout' => 'tall'],
        ['image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC_zM1hH7lEcRN0vTqLJ1cC9ib8MIV_bVW18OK7nw1LQPKtTcDVOTVYqLVzeZxhs1bQRYpH1X0kFFswq-jiwWKOUPneCUia6_zYj6n3hFFRWJZ9cD41pjDZuxpiP9smdeImJpsY4n1lMSdq193Vx5ug8tSfGVb4TEcPlVnrs56bdIghU_an2IDfFBd6dt4NX-xlRw5Rj8WZKvqKKZ8cGcqioZSNc_LERysBZDOlQB0LBiYK_UI12PRT0D04nM8rl-ap2_iJt07mRmk6', 'alt_text' => 'Butir padi hijau di pagi hari', 'layout' => 'normal'],
        ['image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDjsUmzP5tmnRWZUZBMfhROtNE0LN8oBRncEmqIxWRRSiPIrTBfNeup0aeD31t83A5tXjC9rpzJlz4L-rLbZICFYCRTwZJFaHbEB88HxxyhWlHYv4aO2IJh4SfOeJWI9MbzrHLWeRRdX6ZIzI2gv9vHgdy7JacGipKt0fEPaXnScXwOkTafBU7IAY7yJ4bo9CC3JzJEtcBoQTm-NBWVFBbr2YjB0jfsePsLDgXHV9t243uVtmjFx1IptqI4fzFg0uexXv0IcJu091bs', 'alt_text' => 'Warga berkumpul dalam upacara adat', 'layout' => 'wide'],
        ['image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAfwBAkbAkKMM2kzHHNmEl2epwoOkLg-16JyvEr18TEAvxYT9V4E_f6yfFCLXiFq_S8i2tW-kP5nmcdI6-Rpruna81vEHWSlRK4E83itOIsLFWrrV_m_aygmuruRsCV_0JSthBS9a2648EJ3r98eBCd4MGN9H2wtPVMHlPDJHuIW9tfZ9bdIZrfcg8nXJrPMkicZ-ppvn0IX63Qgwe5oKFTWWH4qK5r299cwVXpOgKTMNax9FXqtfdKDqqsV_TXHh49XZz_LqRVBrdX', 'alt_text' => 'Anak desa tersenyum dengan kain tradisional', 'layout' => 'normal'],
        ['image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAzH48ys6yh2SD-hx4c0MKL7lzRDCo0bUJZoQyExIkX6XGRZ5AiTQ5abnVRaACla1F6PN7G4GZk6rysFB8n5mHVW5wWVm-wRAW8wTycoxiBk_cXn9WY70GxcohQcYUlwZ1or21wWmgO_Zk6GgVQCwIShHNv8cohgLWfJeu9wyMy5fGTOH4iazR7KUblEf4EbM5gyY-mUtg5Qp0UNQZKHJcz04UBO7EEDYmqonR6PcoQ76vIPCZmzxTOlQWu3bmt2OaUD1DPmzuXMQT-', 'alt_text' => 'Jalur hutan menuju air terjun tersembunyi', 'layout' => 'tall'],
        ['image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDzonWUEzujALOr6-GRyjaIBTwJU5eSMbSQQs8EWo4WT4L41oqgDl_78N1HtvWPsJuMmGcDmwM-fgtbu4QXIJaJJp06ohcS4snafoABCVawzofMGvQHKt8o_0bwq_jAJR6ytaXu1exblPSXs1jRlf9nOaFDyPwrZodqPyimDWaIJHrvp2IS5kCyxQnDCKoxYqCVPZYcvVsmAyiRYG49H5paTkg6UdgDLH55ZOQgX5UOa972hc-dEP2kwX7sk-iHlQMK9EnSr0-75Bz8', 'alt_text' => 'Bima sakti di atas atap Tongkonan', 'layout' => 'normal'],
    ];

    $beritaList = [
        ['kategori' => 'Pembangunan', 'judul' => 'Musyawarah Perencanaan Desa 2025', 'deskripsi' => 'Membahas arah kebijakan strategis dalam pengalokasian dana desa untuk sektor pariwisata dan infrastruktur dasar.', 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDJhpU4t_M21kEVUMAL_kFE8i92bQNTw5ypA_jC0z5JND1cjq7f2HQvTb6kT6lpuM190Lxp7nPLQJEB5Tm9z37zAY_h8kId-z-5kODO7WyKHPrl2-8kFShGXgFX6Ot4N19nYkrb15TKcUOTOEGKDjPr7JC93rmLuQtOBh3pu_f7ghLNb9sxm7K2IVLcslULlKFOBn4mzHK6a466dnSLdC97InXvL1IaOWajdW66jowpx2FaPueK4ybpO1v1azIssiw0_bjObMjxGFzW', 'tanggal' => '2024-10-12'],
        ['kategori' => 'Budaya', 'judul' => 'Festival Budaya Tambunan Kembali Digelar', 'deskripsi' => 'Kemeriahan perayaan syukur pasca panen yang melibatkan seluruh warga desa dan menarik wisatawan mancanegara.', 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBBuk6G1csmvp8RMbaWfJkUVh3gMdYtsOSoJ32CDlaGuVlr2sJhhjvXo-L_4B-suIjtgyEcpAA_6d04suXoaZvoIBI-7SKbIwwYUQYTlznyuH_OVb2M4duJrlGKXkGZOx1Jktr2eI5l1o81oFkcLDL53YEoTuO0blSGUQOwO1V_i6iWRgzss8RnuiH4udHF4nzpE1USPYa4KCh4Uip35ZZAKXytBBIPrrtiX_F6HafZtfFdCjMwOLIzi7XS3YmbimbUjgke1CGnQOcW', 'tanggal' => '2024-10-05'],
        ['kategori' => 'Pemberdayaan', 'judul' => 'Program Digitalisasi UMKM Desa', 'deskripsi' => 'Pelatihan intensif bagi pelaku usaha lokal dalam memanfaatkan platform e-commerce untuk memasarkan produk tenun.', 'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBlv2uIb5Ot-yMi-MvofYxRL5Xvn84l8ixfUy68nSvKUxNro8IkX0BoG7tVDIrRpAL8htHrpsLDlVsZEiWVpk9sMMlm_F0McrInQOY_Sg85iL1ao0kvQ0uFxuIyBXB1KAedrPXXHshCzKghuar2sYl0BXyk9soVPcUzwGZPqE0rWB1nRbfdmeCPakHEf1IGb46eNfpaHFLKM-KwDrct8mTaDaL9l9jewlASiuIElOTo2rsSCAWeRq-sL8qb32M81OmQdfERoqcbTvi1', 'tanggal' => '2024-09-28'],
    ];
}

function h($val) { return htmlspecialchars((string) $val, ENT_QUOTES); }
?>
<!DOCTYPE html>

<html class="scroll-smooth" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Kelurahan Tambunan | Warisan Budaya &amp; Keindahan Alam</title>
<meta content="Situs resmi Kelurahan Tambunan, Tana Toraja &mdash; profil kelurahan, potensi alam dan budaya, struktur pemerintahan, berita, serta galeri." name="description"/>
<meta content="Kelurahan Tambunan, Tana Toraja, wisata Toraja, Tongkonan, profil kelurahan" name="keywords"/>
<meta content="Kelurahan Tambunan | Warisan Budaya &amp; Keindahan Alam" property="og:title"/>
<meta content="Mengenal potensi alam, budaya, dan kehidupan masyarakat Kelurahan Tambunan." property="og:description"/>
<meta content="website" property="og:type"/>
<link href="assets/favicon.ico" rel="icon" type="image/x-icon"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="js/tailwind-config.js"></script>
<link href="css/style.css" rel="stylesheet"/>
</head>
<body class="bg-background text-on-background font-body-md">
<!-- TopNavBar -->
<header class="fixed top-0 w-full z-50 bg-transparent backdrop-blur-md transition-all duration-300 h-20 flex items-center">
<div class="flex justify-between items-center w-full px-margin-mobile md:px-gutter max-w-container-max-width mx-auto">
<div class="flex items-center gap-2 md:gap-3">
<img alt="Logo Kabupaten Tana Toraja" class="h-7 w-7 md:h-10 md:w-10 object-contain shrink-0" src="assets/images/logo/logo-kecamatan.png"/>
<span class="font-headline-md text-base sm:text-lg md:text-headline-md text-secondary-fixed dark:text-secondary-fixed-dim leading-tight">
                Kelurahan Tambunan
            </span>
</div>
<nav class="hidden md:flex gap-6 items-center">
<a class="nav-link font-label-lg text-label-lg text-on-primary opacity-80 hover:opacity-100 transition-opacity hover:text-secondary-fixed pb-1 border-b-2 border-transparent" data-section="home" href="#home">Home</a>
<a class="nav-link font-label-lg text-label-lg text-on-primary opacity-80 hover:opacity-100 transition-opacity hover:text-secondary-fixed pb-1 border-b-2 border-transparent" data-section="profil-desa" href="#profil-desa">Profil Desa</a>
<div class="relative group">
<button class="font-label-lg text-label-lg text-on-primary opacity-80 hover:opacity-100 transition-opacity hover:text-secondary-fixed pb-1 border-b-2 border-transparent flex items-center gap-1" type="button">
Wisata
<span class="material-symbols-outlined text-base">expand_more</span>
</button>
<div class="absolute top-full left-0 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
<div class="bg-primary luxury-shadow py-2 w-64">
<a class="block px-5 py-3 text-on-primary opacity-80 hover:opacity-100 hover:bg-white/5 transition-all" href="#struktur-pemerintahan">Struktur Pemerintahan</a>
<a class="block px-5 py-3 text-on-primary opacity-80 hover:opacity-100 hover:bg-white/5 transition-all" href="#fasilitas-umum">Fasilitas Umum</a>
<a class="block px-5 py-3 text-on-primary opacity-80 hover:opacity-100 hover:bg-white/5 transition-all" href="#potensi-desa">Potensi Desa</a>
<a class="block px-5 py-3 text-on-primary opacity-80 hover:opacity-100 hover:bg-white/5 transition-all" href="#galeri">Galeri</a>
</div>
</div>
</div>
<div class="relative group">
<button class="font-label-lg text-label-lg text-on-primary opacity-80 hover:opacity-100 transition-opacity hover:text-secondary-fixed pb-1 border-b-2 border-transparent flex items-center gap-1" type="button">
Layanan
<span class="material-symbols-outlined text-base">expand_more</span>
</button>
<div class="absolute top-full left-0 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
<div class="bg-primary luxury-shadow py-2 w-64">
<a class="block px-5 py-3 text-on-primary opacity-80 hover:opacity-100 hover:bg-white/5 transition-all" href="legal-corner.php">Legal Corner</a>
<a class="block px-5 py-3 text-on-primary opacity-80 hover:opacity-100 hover:bg-white/5 transition-all" href="legal-informasi.php">Informasi Hukum</a>
<a class="block px-5 py-3 text-on-primary opacity-80 hover:opacity-100 hover:bg-white/5 transition-all" href="legal-tanya.php">Tanya Hukum</a>
<a class="block px-5 py-3 text-on-primary opacity-80 hover:opacity-100 hover:bg-white/5 transition-all" href="legal-rujukan.php">Rujukan &amp; Kontak Darurat</a>
<a class="block px-5 py-3 text-on-primary opacity-80 hover:opacity-100 hover:bg-white/5 transition-all" href="legal-buku.php">Unduh Buku Saku</a>
</div>
</div>
</div>
<div class="relative group">
<button class="font-label-lg text-label-lg text-on-primary opacity-80 hover:opacity-100 transition-opacity hover:text-secondary-fixed pb-1 border-b-2 border-transparent flex items-center gap-1" type="button">
Berita
<span class="material-symbols-outlined text-base">expand_more</span>
</button>
<div class="absolute top-full left-0 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
<div class="bg-primary luxury-shadow py-2 w-56">
<a class="block px-5 py-3 text-on-primary opacity-80 hover:opacity-100 hover:bg-white/5 transition-all" href="#berita">Berita</a>
<a class="block px-5 py-3 text-on-primary opacity-80 hover:opacity-100 hover:bg-white/5 transition-all" href="artikel.php">Artikel</a>
</div>
</div>
</div>
<a class="nav-link font-label-lg text-label-lg text-on-primary opacity-80 hover:opacity-100 transition-opacity hover:text-secondary-fixed pb-1 border-b-2 border-transparent" data-section="kontak" href="#kontak">Kontak</a>
<a class="bg-gold-accent text-primary px-5 py-2 text-sm font-label-lg uppercase tracking-widest hover:brightness-110 transition-all" href="admin/login.php">Login</a>
</nav>
<button aria-controls="mobile-menu" aria-expanded="false" aria-label="Buka menu" class="md:hidden flex flex-col justify-center items-center w-10 h-10 gap-1.5" id="hamburger-btn">
<span class="block w-6 h-0.5 bg-on-primary transition-all" id="bar1"></span>
<span class="block w-6 h-0.5 bg-on-primary transition-all" id="bar2"></span>
<span class="block w-6 h-0.5 bg-on-primary transition-all" id="bar3"></span>
</button>
</div>
<nav class="hidden md:hidden flex-col gap-1 bg-primary px-margin-mobile py-6 max-h-[calc(100vh-5rem)] overflow-y-auto absolute top-20 left-0 w-full" id="mobile-menu">
<a class="font-label-lg text-label-lg text-on-primary py-3 border-b border-white/10" href="#home">Home</a>
<a class="font-label-lg text-label-lg text-on-primary py-3 border-b border-white/10" href="#profil-desa">Profil Desa</a>
<span class="text-gold-accent text-xs uppercase tracking-widest pt-4 pb-1 block">Wisata</span>
<a class="font-label-lg text-label-lg text-on-primary py-2 pl-3 border-b border-white/10" href="#struktur-pemerintahan">Struktur Pemerintahan</a>
<a class="font-label-lg text-label-lg text-on-primary py-2 pl-3 border-b border-white/10" href="#fasilitas-umum">Fasilitas Umum</a>
<a class="font-label-lg text-label-lg text-on-primary py-2 pl-3 border-b border-white/10" href="#potensi-desa">Potensi Desa</a>
<a class="font-label-lg text-label-lg text-on-primary py-2 pl-3 border-b border-white/10" href="#galeri">Galeri</a>
<span class="text-gold-accent text-xs uppercase tracking-widest pt-4 pb-1 block">Layanan</span>
<a class="font-label-lg text-label-lg text-on-primary py-2 pl-3 border-b border-white/10" href="legal-corner.php">Legal Corner</a>
<a class="font-label-lg text-label-lg text-on-primary py-2 pl-3 border-b border-white/10" href="legal-informasi.php">Informasi Hukum</a>
<a class="font-label-lg text-label-lg text-on-primary py-2 pl-3 border-b border-white/10" href="legal-tanya.php">Tanya Hukum</a>
<a class="font-label-lg text-label-lg text-on-primary py-2 pl-3 border-b border-white/10" href="legal-rujukan.php">Rujukan &amp; Kontak Darurat</a>
<a class="font-label-lg text-label-lg text-on-primary py-2 pl-3 border-b border-white/10" href="legal-buku.php">Unduh Buku Saku</a>
<span class="text-gold-accent text-xs uppercase tracking-widest pt-4 pb-1 block">Berita</span>
<a class="font-label-lg text-label-lg text-on-primary py-2 pl-3 border-b border-white/10" href="#berita">Berita</a>
<a class="font-label-lg text-label-lg text-on-primary py-2 pl-3 border-b border-white/10" href="artikel.php">Artikel</a>
<a class="font-label-lg text-label-lg text-on-primary py-3 mt-2" href="#kontak">Kontak</a>
</nav>
</header>
<!-- Hero Section -->
<section class="relative h-screen flex items-center overflow-hidden" id="home">
<div class="absolute inset-0 z-0">
<img class="w-full h-full object-cover scale-105 animate-slow-zoom" src="<?= h($heroImage) ?>" alt="Foto suasana Kelurahan Tambunan" loading="lazy"/>
<div class="absolute inset-0 bg-primary/40 backdrop-brightness-75"></div>
</div>
<div class="relative z-10 max-w-container-max-width mx-auto px-margin-mobile md:px-gutter w-full">
<div class="max-w-3xl">
<h1 class="font-display-lg text-display-lg md:text-display-lg text-white mb-6">Kelurahan Tambunan</h1>
<p class="font-body-lg text-body-lg text-white/90 mb-10 leading-relaxed max-w-2xl">
                    Mengenal potensi alam, budaya, dan kehidupan masyarakat sebagai langkah menuju pengembangan desa wisata yang berkelanjutan.
                </p>
<div class="flex flex-col sm:flex-row gap-4">
<a class="bg-gold-accent text-primary px-10 py-4 font-label-lg uppercase tracking-widest hover:brightness-110 transition-all text-center" href="https://www.google.com/maps/search/?api=1&amp;query=Kantor+Lurah+Tambunan+Tana+Toraja" rel="noopener noreferrer" target="_blank">
                        Explore Village
                    </a>
<a class="border border-white text-white px-10 py-4 font-label-lg uppercase tracking-widest hover:bg-white hover:text-primary transition-all text-center" href="#profil-desa">
                        View Village Profile
                    </a>
</div>
</div>
</div>
</section>
<!-- About Section -->
<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface" id="profil-desa">
<div class="max-w-container-max-width mx-auto px-margin-mobile md:px-gutter">
<div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
<div class="relative">
<img class="w-full aspect-[4/5] object-cover luxury-shadow rounded-none" src="<?= h($profilImage) ?>" alt="Foto pengrajin Kelurahan Tambunan" loading="lazy"/>
<div class="absolute -bottom-8 -right-8 w-48 h-48 bg-primary-container p-6 hidden md:flex flex-col items-center justify-center text-center">
<span class="text-gold-accent font-display-md text-display-md leading-none">1902</span>
<span class="text-on-primary-container font-label-md uppercase tracking-wider mt-2">Established History</span>
</div>
</div>
<div>
<span class="text-gold-accent font-label-lg uppercase tracking-widest mb-4 block">Introduction</span>
<h2 class="font-headline-lg text-headline-lg text-primary mb-8">Harmoni Tradisi dan Kelestarian Alam</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                        Terletak di jantung pegunungan Sulawesi, Kelurahan Tambunan merupakan permata tersembunyi yang menjaga erat tradisi leluhur Toraja. Kami berkomitmen untuk membangun masa depan yang berkelanjutan tanpa melupakan akar budaya yang telah membentuk identitas kami selama berabad-abad.
                    </p>
</div>
</div>
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-8 mt-16" id="stats-grid">
<div class="border-l border-primary/10 pl-6">
<span class="block font-display-md text-headline-md text-primary"><?= h(number_format((float)$stats['penduduk'], 0, ',', '.')) ?></span>
<span class="font-label-md text-on-surface-variant uppercase">Penduduk</span>
</div>
<div class="border-l border-primary/10 pl-6">
<span class="block font-display-md text-headline-md text-primary"><?= h(number_format((float)$stats['kepala_keluarga'], 0, ',', '.')) ?></span>
<span class="font-label-md text-on-surface-variant uppercase">Kepala Keluarga</span>
</div>
<div class="border-l border-primary/10 pl-6">
<span class="block font-display-md text-headline-md text-primary"><?= h(number_format((float)$stats['luas_wilayah'], 1, ',', '.')) ?></span>
<span class="font-label-md text-on-surface-variant uppercase">Luas Wilayah (km²)</span>
</div>
<div class="border-l border-primary/10 pl-6">
<span class="block font-display-md text-headline-md text-primary"><?= h($stats['jumlah_dusun']) ?></span>
<span class="font-label-md text-on-surface-variant uppercase">Dusun</span>
</div>
<div class="border-l border-primary/10 pl-6">
<span class="block font-display-md text-headline-md text-primary"><?= h(number_format((float)$stats['ketinggian'], 0, ',', '.')) ?></span>
<span class="font-label-md text-on-surface-variant uppercase">Ketinggian (mdpl)</span>
</div>
</div>
</div>
</section>
<!-- Struktur Pemerintahan Section -->
<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface-container-low" id="struktur-pemerintahan">
<div class="max-w-container-max-width mx-auto px-margin-mobile md:px-gutter">
<div class="text-center mb-20">
<span class="text-gold-accent font-label-lg uppercase tracking-widest mb-4 block">Pemerintahan Kelurahan</span>
<h2 class="font-headline-lg text-headline-lg text-primary">Struktur Pemerintahan Kelurahan</h2>
</div>
<div class="max-w-2xl mx-auto mb-12 luxury-shadow bg-surface-container-lowest p-8 flex items-center gap-6">
<span class="material-symbols-outlined text-gold-accent text-4xl">badge</span>
<div>
<span class="font-label-md text-on-surface-variant uppercase tracking-wider block mb-1">Lurah Tambunan</span>
<h3 class="font-headline-sm text-headline-sm text-primary"><?= h($lurah['nama']) ?></h3>
<a class="font-label-md text-on-surface-variant hover:text-gold-accent transition-colors" href="tel:<?= h($lurah['no_hp']) ?>"><?= h($lurah['no_hp']) ?></a>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<?php foreach ($lingkunganList as $lk): ?>
<div class="bg-surface-container-lowest luxury-shadow p-8">
<h3 class="font-headline-sm text-headline-sm text-primary mb-1"><?= h($lk['nama']) ?></h3>
<p class="font-label-md text-on-surface-variant mb-6"><?= h($lk['kepala_nama']) ?> &middot; <a class="hover:text-gold-accent transition-colors" href="tel:<?= h($lk['kepala_hp']) ?>"><?= h($lk['kepala_hp']) ?></a></p>
<ul class="space-y-4">
<?php foreach ($lk['rt'] as $rt): ?>
<li class="border-t border-primary/10 pt-4 flex justify-between items-center gap-4">
<span class="text-on-surface">RT <?= h($rt['nama']) ?><br/><span class="text-sm text-on-surface-variant"><?= h($rt['ketua_nama']) ?></span></span>
<a class="text-sm text-on-surface-variant hover:text-gold-accent transition-colors whitespace-nowrap" href="tel:<?= h($rt['ketua_hp']) ?>"><?= h($rt['ketua_hp']) ?></a>
</li>
<?php endforeach; ?>
</ul>
</div>
<?php endforeach; ?>
</div>
</div>
</section>
<!-- Fasilitas Umum Section -->
<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface" id="fasilitas-umum">
<div class="max-w-container-max-width mx-auto px-margin-mobile md:px-gutter">
<div class="text-center mb-16">
<span class="text-gold-accent font-label-lg uppercase tracking-widest mb-4 block">Peta Fasilitas</span>
<h2 class="font-headline-lg text-headline-lg text-primary">Fasilitas Umum</h2>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-4xl mx-auto">
<?php foreach ($fasilitasList as $f): ?>
<div class="flex items-center justify-between gap-4 bg-surface-container-lowest luxury-shadow px-6 py-4">
<span class="text-on-surface"><?= h($f['nama']) ?></span>
<?php if (!empty($f['gmaps_link'])): ?>
<a class="shrink-0 flex items-center gap-1 text-xs uppercase tracking-wider text-primary border border-primary/30 px-3 py-1.5 hover:bg-primary hover:text-on-primary transition-all" href="<?= h($f['gmaps_link']) ?>" rel="noopener noreferrer" target="_blank">
<span class="material-symbols-outlined text-sm">location_on</span>
                        Maps
                    </a>
<?php else: ?>
<span class="shrink-0 flex items-center gap-1 text-xs uppercase tracking-wider text-on-surface-variant/50 border border-outline-variant px-3 py-1.5 cursor-not-allowed">
<span class="material-symbols-outlined text-sm">location_off</span>
                        Maps
                    </span>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
</div>
</section>
<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface-container-low" id="potensi-desa">
<div class="max-w-container-max-width mx-auto px-margin-mobile md:px-gutter">
<div class="text-center mb-20">
<span class="text-gold-accent font-label-lg uppercase tracking-widest mb-4 block">Resources &amp; Legacy</span>
<h2 class="font-headline-lg text-headline-lg text-primary">Potensi Desa</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
<?php foreach ($potensiList as $p): ?>
<div class="group bg-surface-container-lowest luxury-shadow overflow-hidden transition-transform duration-500 hover:-translate-y-2">
<img class="w-full h-64 object-cover transition-transform duration-700 group-hover:scale-110" src="<?= h($p['image_path']) ?>" alt="<?= h($p['judul']) ?>" loading="lazy"/>
<div class="p-8">
<h3 class="font-headline-sm text-headline-sm text-primary mb-3"><?= h($p['judul']) ?></h3>
<p class="text-on-surface-variant mb-6 line-clamp-3 text-sm"><?= h($p['deskripsi']) ?></p>
<div class="h-px w-8 bg-gold-accent group-hover:w-full transition-all duration-500"></div>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
</section>
<!-- Vision Roadmap Section -->
<section class="py-section-gap-mobile md:py-section-gap-desktop bg-primary text-on-primary">
<div class="max-w-container-max-width mx-auto px-margin-mobile md:px-gutter">
<div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
<div class="lg:col-span-1">
<span class="text-gold-accent font-label-lg uppercase tracking-widest mb-4 block">Strategic Future</span>
<h2 class="font-headline-lg text-headline-lg mb-8">Menuju Desa Wisata Mandiri</h2>
<p class="font-body-md text-on-primary/70 leading-relaxed mb-8">
                        Visi strategis kami adalah mentransformasi potensi Kelurahan Tambunan menjadi destinasi wisata kelas dunia yang menjunjung tinggi prinsip keberlanjutan dan pemberdayaan masyarakat lokal.
                    </p>
<button class="flex items-center gap-3 text-gold-accent font-label-lg uppercase hover:gap-5 transition-all">
                        Pelajari Roadmap <span class="material-symbols-outlined">arrow_right_alt</span>
</button>
</div>
<div class="lg:col-span-2 space-y-12 relative before:absolute before:left-6 md:before:left-1/2 before:top-4 before:bottom-4 before:w-px before:bg-white/10">
<div class="relative flex flex-col md:flex-row items-center md:justify-between group">
<div class="md:w-5/12 text-left md:text-right order-2 md:order-1 mt-4 md:mt-0">
<h4 class="font-headline-sm text-headline-sm text-gold-accent">Pondasi Infrastruktur</h4>
<p class="text-on-primary/60 mt-2">Pengembangan aksesibilitas dan fasilitas dasar berbasis lingkungan.</p>
</div>
<div class="absolute left-6 md:left-1/2 -translate-x-1/2 w-4 h-4 bg-gold-accent rounded-full z-10 border-4 border-primary"></div>
<div class="md:w-5/12 order-3"></div>
</div>
<div class="relative flex flex-col md:flex-row items-center md:justify-between group">
<div class="md:w-5/12 order-1"></div>
<div class="absolute left-6 md:left-1/2 -translate-x-1/2 w-4 h-4 bg-white/30 rounded-full z-10 border-4 border-primary group-hover:bg-gold-accent transition-colors"></div>
<div class="md:w-5/12 text-left order-2 mt-4 md:mt-0">
<h4 class="font-headline-sm text-headline-sm text-white">Digitalisasi &amp; Edukasi</h4>
<p class="text-on-primary/60 mt-2">Pelatihan pemandu lokal dan peluncuran platform digital desa.</p>
</div>
</div>
<div class="relative flex flex-col md:flex-row items-center md:justify-between group">
<div class="md:w-5/12 text-left md:text-right order-2 md:order-1 mt-4 md:mt-0">
<h4 class="font-headline-sm text-headline-sm text-white">Ekosistem Wisata</h4>
<p class="text-on-primary/60 mt-2">Integrasi UMKM, atraksi budaya, dan akomodasi homestay autentik.</p>
</div>
<div class="absolute left-6 md:left-1/2 -translate-x-1/2 w-4 h-4 bg-white/30 rounded-full z-10 border-4 border-primary group-hover:bg-gold-accent transition-colors"></div>
<div class="md:w-5/12 order-3"></div>
</div>
</div>
</div>
</div>
</section>
<!-- Gallery Section (Masonry) -->
<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface" id="galeri">
<div class="max-w-container-max-width mx-auto px-margin-mobile md:px-gutter">
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-8">
<div class="max-w-xl">
<span class="text-gold-accent font-label-lg uppercase tracking-widest mb-4 block">Visual Journey</span>
<h2 class="font-headline-lg text-headline-lg text-primary">Jendela Desa</h2>
</div>
<button class="font-label-lg text-on-surface-variant border-b border-outline-variant pb-1 opacity-50 cursor-not-allowed flex items-center gap-2" disabled type="button">
                    Lihat Galeri Lengkap
                    <span class="text-[10px] uppercase tracking-wider border border-outline-variant px-2 py-0.5 rounded-full normal-case">Segera Hadir</span>
                </button>
</div>
<div class="masonry-grid">
<?php foreach ($galeriList as $g):
    $layoutClass = $g['layout'] === 'tall' ? 'masonry-item-tall' : ($g['layout'] === 'wide' ? 'masonry-item-wide' : '');
?>
<div class="<?= h($layoutClass) ?> group relative overflow-hidden">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="<?= h($g['image_path']) ?>" alt="<?= h($g['alt_text']) ?>" loading="lazy"/>
</div>
<?php endforeach; ?>
</div>
</div>
</section>
<!-- News Section -->
<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface-container" id="berita">
<div class="max-w-container-max-width mx-auto px-margin-mobile md:px-gutter">
<div class="mb-16">
<span class="text-gold-accent font-label-lg uppercase tracking-widest mb-4 block">Village Updates</span>
<h2 class="font-headline-lg text-headline-lg text-primary">Berita Terbaru</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-12">
<?php foreach ($beritaList as $b): ?>
<article class="group">
<div class="aspect-video mb-8 overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?= h($b['image_path']) ?>" alt="<?= h($b['judul']) ?>" loading="lazy"/>
</div>
<span class="text-label-md font-label-md text-on-surface-variant uppercase mb-3 block"><?= h($b['kategori']) ?></span>
<h3 class="font-headline-sm text-headline-sm text-primary mb-4 group-hover:text-gold-accent transition-colors"><?= h($b['judul']) ?></h3>
<p class="text-on-surface-variant line-clamp-3 mb-6"><?= h($b['deskripsi']) ?></p>
<time class="text-label-md font-label-md text-on-surface-variant/60"><?= h(date('d F Y', strtotime($b['tanggal']))) ?></time>
</article>
<?php endforeach; ?>
</div>
</div>
</section>
<!-- Contact & Map Section -->
<section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface" id="kontak">
<div class="max-w-container-max-width mx-auto px-margin-mobile md:px-gutter">
<div class="flex flex-col lg:flex-row luxury-shadow bg-surface-container-lowest overflow-hidden">
<div class="lg:w-1/3 p-12 bg-primary text-on-primary">
<h2 class="font-headline-sm text-headline-sm mb-8">Hubungi Kami</h2>
<p class="text-on-primary/70 mb-10">Pintu kami selalu terbuka bagi Anda yang ingin berkunjung, bekerja sama, atau sekadar bertukar kabar.</p>
<div class="space-y-8">
<div class="flex items-start gap-4">
<span class="material-symbols-outlined text-gold-accent">location_on</span>
<div>
<p class="font-label-lg">Kantor Kelurahan Tambunan</p>
<p class="text-sm text-on-primary/60 mt-1">Kecamatan Makale Utara, Tana Toraja, Sulawesi Selatan 91811</p>
</div>
</div>
<div class="flex items-start gap-4">
<span class="material-symbols-outlined text-gold-accent">call</span>
<div>
<p class="font-label-lg">Telepon</p>
<p class="text-sm text-on-primary/60 mt-1">+62 (423) 1234-5678</p>
</div>
</div>
<div class="flex items-start gap-4">
<span class="material-symbols-outlined text-gold-accent">mail</span>
<div>
<p class="font-label-lg">Email</p>
<p class="text-sm text-on-primary/60 mt-1">info@tambunan.desa.id</p>
</div>
</div>
</div>
<div class="mt-16 flex gap-6">
<a aria-label="Facebook" class="w-10 h-10 border border-white/20 flex items-center justify-center hover:bg-gold-accent hover:border-gold-accent transition-all" href="#" rel="noopener noreferrer" target="_blank"><span class="material-symbols-outlined text-sm">thumb_up</span></a>
<a aria-label="Instagram" class="w-10 h-10 border border-white/20 flex items-center justify-center hover:bg-gold-accent hover:border-gold-accent transition-all" href="#" rel="noopener noreferrer" target="_blank"><span class="material-symbols-outlined text-sm">photo_camera</span></a>
<a aria-label="YouTube" class="w-10 h-10 border border-white/20 flex items-center justify-center hover:bg-gold-accent hover:border-gold-accent transition-all" href="#" rel="noopener noreferrer" target="_blank"><span class="material-symbols-outlined text-sm">smart_display</span></a>
</div>
</div>
<div class="w-full lg:w-2/3 aspect-[4/3] lg:aspect-auto lg:h-auto lg:min-h-[500px] relative bg-surface-container-lowest">
<a href="assets/images/peta/peta-administrasi.jpg" rel="noopener noreferrer" target="_blank">
<img alt="Peta Administrasi Kelurahan Tambunan, Kec. Makale Utara, Kab. Tana Toraja" class="w-full h-full object-contain" loading="lazy" src="assets/images/peta/peta-administrasi.jpg"/>
</a>
<a class="absolute bottom-4 right-4 bg-surface-container-lowest text-primary text-sm px-4 py-2.5 luxury-shadow hover:bg-gold-accent hover:text-primary transition-all flex items-center gap-2" href="https://www.google.com/maps/search/?api=1&amp;query=Kantor+Lurah+Tambunan+Tana+Toraja" rel="noopener noreferrer" target="_blank">
<span class="material-symbols-outlined text-base">open_in_new</span>
                        Buka di Google Maps
                    </a>
</div>
</div>
</div>
</section>
<!-- Footer -->
<footer class="bg-primary dark:bg-primary-container text-on-primary">
<div class="w-full py-section-gap-mobile md:py-section-gap-desktop px-margin-mobile md:px-gutter flex flex-col md:flex-row justify-between items-start max-w-container-max-width mx-auto">
<div class="mb-12 md:mb-0 max-w-sm">
<div class="font-headline-sm text-headline-sm text-secondary-fixed mb-6">Kelurahan Tambunan</div>
<p class="font-body-md text-body-md opacity-70 mb-8">
                    Menjaga tradisi, merawat alam, membangun masa depan yang berkelanjutan bagi generasi mendatang.
                </p>
<div class="flex flex-wrap gap-4">
<a class="text-on-primary opacity-70 hover:text-secondary-fixed-dim underline transition-all font-label-md" href="#">Kebijakan Privasi</a>
<a class="text-on-primary opacity-70 hover:text-secondary-fixed-dim underline transition-all font-label-md" href="#">Syarat &amp; Ketentuan</a>
<a class="text-on-primary opacity-70 hover:text-secondary-fixed-dim underline transition-all font-label-md" href="#">Peta Situs</a>
<a class="text-on-primary opacity-50 hover:opacity-90 hover:text-secondary-fixed-dim underline transition-all font-label-md" href="admin/login.php">Login Admin</a>
</div>
</div>
<div class="grid grid-cols-2 gap-16">
<div>
<h4 class="font-label-lg uppercase tracking-widest text-gold-accent mb-6">Navigasi</h4>
<ul class="space-y-4">
<li><a class="opacity-70 hover:text-secondary-fixed-dim transition-all" href="#home">Home</a></li>
<li><a class="opacity-70 hover:text-secondary-fixed-dim transition-all" href="#profil-desa">Profil Desa</a></li>
<li><a class="opacity-70 hover:text-secondary-fixed-dim transition-all" href="#fasilitas-umum">Fasilitas Umum</a></li>
<li><a class="opacity-70 hover:text-secondary-fixed-dim transition-all" href="#potensi-desa">Potensi</a></li>
<li><a class="opacity-70 hover:text-secondary-fixed-dim transition-all" href="#berita">Berita</a></li>
<li><a class="opacity-70 hover:text-secondary-fixed-dim transition-all" href="artikel.php">Artikel</a></li>
<li><a class="opacity-70 hover:text-secondary-fixed-dim transition-all" href="legal-corner.php">Legal Corner</a></li>
</ul>
</div>
<div>
<h4 class="font-label-lg uppercase tracking-widest text-gold-accent mb-6">Layanan</h4>
<ul class="space-y-4">
<li class="flex items-center gap-2 opacity-50 cursor-not-allowed">Pengaduan <span class="text-[10px] uppercase tracking-wider border border-white/30 px-2 py-0.5 rounded-full">Segera Hadir</span></li>
<li class="flex items-center gap-2 opacity-50 cursor-not-allowed">Informasi Publik <span class="text-[10px] uppercase tracking-wider border border-white/30 px-2 py-0.5 rounded-full">Segera Hadir</span></li>
<li class="flex items-center gap-2 opacity-50 cursor-not-allowed">Surat Online <span class="text-[10px] uppercase tracking-wider border border-white/30 px-2 py-0.5 rounded-full">Segera Hadir</span></li>
<li class="flex items-center gap-2 opacity-50 cursor-not-allowed">BUMDes <span class="text-[10px] uppercase tracking-wider border border-white/30 px-2 py-0.5 rounded-full">Segera Hadir</span></li>
</ul>
</div>
</div>
</div>
<div class="max-w-container-max-width mx-auto px-margin-mobile md:px-gutter py-8 border-t border-white/10 text-center md:text-left">
<p class="font-label-md text-label-md opacity-50">© 2024 Pemerintah Kelurahan Tambunan. All Rights Reserved.</p>
</div>
</footer>
<script src="js/main.js"></script>
</body></html>