# Project Context — Website Kelurahan Tambunan

Dokumen ini dibuat supaya AI/asisten lain (atau developer lain) bisa lanjutin kerjaan ini **tanpa merusak apa yang sudah ada**. Kalau kamu AI yang baca ini: baca semua sebelum mengubah apapun, terutama bagian "ATURAN PENTING" di bawah.

---

## ATURAN PENTING (WAJIB DIPATUHI)

1. **JANGAN UBAH DESAIN VISUAL** tanpa diminta eksplisit oleh user. Warna, font, layout, spacing semua sudah difinalisasi berdasarkan desain asli dari Google Stitch. User sangat sensitif soal ini — sudah bilang berkali-kali "jangan ubah apa-apa soal desainnya".
2. **Styling pakai Tailwind CSS via CDN** dengan config custom di `js/tailwind-config.js` (design tokens: warna `primary`, `gold-accent`, dll — jangan pakai warna hex sembarangan, selalu pakai token yang sudah didefinisikan di situ).
3. **Font:** Playfair Display (judul) + Inter (body teks). Jangan ganti.
4. Sebelum ubah file besar, **selalu test dengan PHP built-in server + MySQL lokal** sebelum dikasih ke user (lihat bagian Testing di bawah). User sudah terbiasa saya test dulu sebelum kasih file, dan pernah ketemu beberapa bug yang untungnya ketauan sebelum sampai ke dia.
5. User **tidak punya background teknis** (belajar dari nol). Jelaskan istilah teknis dengan bahasa awam, kasih instruksi step-by-step yang sangat detail (dia sukses ikutin instruksi install XAMPP, phpMyAdmin, dll dari nol).
6. User pakai **Bahasa Indonesia** casual — selalu balas dalam Bahasa Indonesia.

---

## Konteks Proyek

- **Untuk:** Website resmi Kelurahan Tambunan, Kec. Makale Utara, Kab. Tana Toraja
- **User:** Mahasiswa/individu yang mengerjakan ini (kemungkinan terkait KKN Universitas Hasanuddin), akan deploy sendiri, repo diserahkan ke Kominfo tapi secara teknis dia yang pegang kendali
- **Yang akan pakai dashboard admin:** Pak Lurah (1 akun admin saja, awam teknologi) — makanya UI dashboard harus sangat sederhana, bahasa awam, tombol jelas
- **Environment testing:** XAMPP di laptop Windows (drive E:, karena C: penuh), folder di `E:\xampp\htdocs\desa-tambunan`

## Tech Stack

- **Frontend:** HTML (dirender PHP) + Tailwind CSS (CDN) + Vanilla JS (tanpa framework)
- **Backend:** PHP native (tanpa framework) + MySQL/MariaDB via PDO (prepared statements)
- **Alasan PHP native (bukan Laravel dkk):** scope kecil, gampang deploy di shared hosting biasa (umum dipakai website pemerintah desa)

## Struktur Folder

```
desa-tambunan/
├── index.php                  # Halaman publik utama (render dari database)
├── artikel.php / artikel-detail.php   # Daftar & detail Artikel (tulisan panjang)
├── legal-corner.php           # Landing page Legal Corner
├── legal-informasi.php        # Sub-halaman: Informasi Hukum
├── legal-tanya.php            # Sub-halaman: Tanya Hukum (form publik + FAQ)
├── legal-rujukan.php          # Sub-halaman: Rujukan Layanan
├── legal-buku.php             # Sub-halaman: Unduh Buku Saku (PDF)
├── legal-darurat.php          # Sub-halaman: Kontak Darurat
├── css/style.css               # CSS custom (di luar Tailwind utility)
├── js/
│   ├── tailwind-config.js      # Design tokens Tailwind (WAJIB pakai token ini utk warna)
│   └── main.js                 # Scroll effect header, hamburger mobile, scrollspy nav
├── admin/
│   ├── login.php                # Form login admin
│   ├── dashboard.php            # Dashboard CRUD utama (Statistik, Lurah, Lingkungan/RT, Hero, Profil, Potensi, Galeri, Berita)
│   ├── dashboard.js             # Handler JS dashboard.php
│   ├── fasilitas.php            # Kelola Fasilitas Umum
│   ├── artikel.php              # Kelola Artikel
│   └── legal-corner.php         # Kelola Legal Corner (5 tab: Informasi/Tanya/Layanan/Buku/Darurat)
├── api/                         # Endpoint backend, semua return JSON
│   ├── login.php / logout.php
│   ├── stats.php (GET publik) / stats-update.php (POST admin)
│   ├── lurah-update.php
│   ├── lingkungan-save.php / lingkungan-delete.php
│   ├── rt-save.php / rt-delete.php
│   ├── hero-update.php / profil-update.php
│   ├── potensi-save.php / potensi-delete.php
│   ├── galeri-save.php / galeri-delete.php
│   ├── berita-save.php / berita-delete.php
│   ├── fasilitas-save.php / fasilitas-delete.php
│   ├── artikel-save.php / artikel-delete.php
│   ├── legal-informasi-save.php / legal-informasi-delete.php
│   ├── legal-layanan-save.php / legal-layanan-delete.php
│   ├── legal-buku-save.php (upload PDF) / legal-buku-delete.php
│   ├── legal-darurat-save.php / legal-darurat-delete.php
│   ├── legal-tanya-submit.php (POST publik, tanpa login — submit pertanyaan)
│   ├── legal-faq-answer.php (POST admin — jawab pertanyaan)
│   ├── legal-faq-delete.php (POST admin — hapus pertanyaan/spam)
│   └── struktur.php (GET publik, tidak dipakai index.php krn index.php query DB langsung)
├── includes/
│   ├── db.php                   # Koneksi PDO — kredensial DB (user perlu edit saat deploy ke hosting)
│   ├── auth.php                 # Session, require_login(), CSRF token
│   ├── upload.php               # handle_image_upload() utk foto, handle_pdf_upload() utk PDF (Buku Saku)
│   └── partials/
│       ├── nav.php              # Header+navbar dropdown (dipakai semua halaman BARU, bukan index.php)
│       └── footer.php           # Footer (dipakai semua halaman BARU, bukan index.php)
├── sql/
│   ├── schema.sql               # Tabel inti: admins, village_stats, lurah, lingkungan, rt
│   ├── migration_content.sql    # Tabel: hero, profil, potensi, galeri, berita
│   └── migration_expansion.sql  # Tabel: fasilitas_umum, artikel, legal_informasi, legal_faq, legal_layanan, legal_buku, legal_darurat
├── assets/
│   ├── images/                  # Folder upload foto (hero/, profil/, potensi/, galeri/, berita/, artikel/, logo/, peta/)
│   └── files/buku-saku/         # Folder upload PDF Buku Saku
└── README.md                    # Panduan setup untuk user (bahasa awam)
```

⚠️ **PENTING soal `index.php` vs halaman baru:** `index.php` punya nav+footer sendiri yang di-inline langsung di file (BUKAN pakai `includes/partials/nav.php`/`footer.php`) — ini supaya scrollspy & smooth-scroll section-nya (yang sudah teruji jalan) tidak berisiko rusak. Kalau nanti update nav/footer, **HARUS diubah di 2 tempat**: langsung di `index.php` (inline) DAN di `includes/partials/nav.php`+`footer.php` (dipakai 8 halaman lainnya). Ini trade-off keamanan vs DRY yang disengaja.

## Database Schema (ringkas)

| Tabel | Isi | CRUD di Dashboard? |
|---|---|---|
| `admins` | id, username, password_hash | Tidak ada UI ganti password — user pilih cara manual lewat phpMyAdmin |
| `village_stats` | id=1 (fixed), penduduk, kepala_keluarga, luas_wilayah, jumlah_dusun, ketinggian | ✅ |
| `lurah` | id=1 (fixed), nama, no_hp | ✅ |
| `lingkungan` | id, nama, kepala_nama, kepala_hp, urutan | ✅ (full CRUD) |
| `rt` | id, lingkungan_id (FK cascade), nama, ketua_nama, ketua_hp, urutan | ✅ (full CRUD, nested di bawah lingkungan) |
| `hero` | id=1 (fixed), image_path | ✅ (upload only) |
| `profil` | id=1 (fixed), image_path | ✅ (upload only) |
| `potensi` | id, judul, deskripsi, image_path, urutan | ✅ (full CRUD) |
| `galeri` | id, image_path, alt_text, layout (enum: normal/tall/wide), urutan | ✅ (full CRUD) |
| `berita` | id, judul, **kategori** (bukan sub_kegiatan!), deskripsi, image_path, tanggal, urutan | ✅ (full CRUD) |
| `fasilitas_umum` | id, nama, gmaps_link (nullable — NULL = tombol Maps abu-abu/disabled), urutan | ✅ (full CRUD) |
| `artikel` | id, judul, ringkasan, konten (long text), penulis, image_path, tanggal, urutan | ✅ (full CRUD, ada halaman detail terpisah `artikel-detail.php?id=X`) |
| `legal_informasi` | id, judul, isi, urutan | ✅ (full CRUD) |
| `legal_faq` | id, nama, email, pertanyaan, jawaban (nullable), status (pending/dijawab), tampil_publik (0/1), created_at | ✅ (admin cuma jawab+toggle publik+hapus, TIDAK bisa "tambah" — pertanyaan hanya masuk dari form publik `legal-tanya-submit.php`) |
| `legal_layanan` | id, nama_instansi, deskripsi, kontak, urutan | ✅ (full CRUD) |
| `legal_buku` | id, judul, deskripsi, file_path (PDF), urutan | ✅ (full CRUD, upload PDF bukan gambar) |
| `legal_darurat` | id, nama, nomor, urutan | ✅ (full CRUD) |

⚠️ **Catatan penamaan kolom:** tabel `berita` pakai nama kolom `kategori`, BUKAN `sub_kegiatan` — sempat ada inkonsistensi waktu development, sudah difinalkan pakai `kategori`. Field ini menampilkan label seperti "Pembangunan", "Budaya", "Pemberdayaan" di kartu berita.

**Cara import di phpMyAdmin:** urutan **WAJIB**: `schema.sql` → `migration_content.sql` → `migration_expansion.sql`. Semua file migration aman diimport ulang kapan saja (pakai `IF NOT EXISTS` dan `ON DUPLICATE KEY UPDATE id = id`) — tidak akan reset data yang sudah diubah user.

## Kredensial Default (WAJIB diganti sebelum go-live)

- **Login admin:** `admin` / `GantiSegera123!`
- **Database lokal (XAMPP):** user `root`, password kosong (`''`)
- User belum ganti password admin per percakapan terakhir — kalau ditanya lagi, tawarkan generate hash bcrypt via `password_hash()` atau suruh dia bikin file sementara utk generate hash sendiri (lalu HAPUS file itu).

## Keamanan yang Sudah Diterapkan

- Password di-hash bcrypt (`password_hash()`)
- Semua query pakai PDO prepared statements
- CSRF token di semua form admin (`csrf_token()` / `verify_csrf()` di `includes/auth.php`)
- Session-based auth, auto-logout 30 menit idle (`require_login()`)
- Rate limiting sederhana di login (5x gagal → cooldown 5 menit, berbasis session)
- Validasi upload: tipe file (jpg/png/webp only via `finfo`), ukuran maks 5MB, nama file di-random (`bin2hex(random_bytes(6))`)

## Pola Kode yang Dipakai (ikuti pola ini kalau nambah fitur baru)

**Menambah CRUD baru (misal fitur X):**
1. Tambah `CREATE TABLE IF NOT EXISTS` + seed data di `sql/migration_content.sql`
2. Bikin `api/x-save.php` (POST, cek `require_login(true)`, cek CSRF, validasi input, upload foto kalau ada pakai `handle_image_upload()`, INSERT/UPDATE)
3. Bikin `api/x-delete.php` kalau perlu hapus
4. Di `index.php`: tambah fetch query di blok `try{}`, tambah fallback array hardcoded di blok `catch{}` (supaya situs tetap jalan kalau DB belum siap), ganti HTML statis jadi `foreach` loop
5. Di `admin/dashboard.php`: tambah section baru dengan form (pola: card putih `bg-surface-container-lowest luxury-shadow p-6/p-8`), fetch data di atas sebelum HTML mulai
6. Kalau perlu "tambah baru" dinamis: tambah `<template id="tpl-x-card">` di akhir file
7. Di `admin/dashboard.js`: tambah handler submit (masuk ke delegasi `document.addEventListener('submit', ...)` yang sudah ada), handler delete, handler tombol tambah baru
8. **WAJIB test end-to-end** sebelum dikasih ke user: `php -l` semua file, cek keseimbangan tag (`<div>` vs `</div>` dll pakai python count), lalu jalankan betulan (`service mariadb start` + `php -S 127.0.0.1:8080` + curl test lengkap: login → CRUD → cek tampil di publik)
9. Reset database test ke kondisi bersih sebelum render preview & zip final

**Catatan penting soal environment testing (sandbox Claude):**
- Setiap `bash_tool` call = shell session BARU. Service seperti `service mariadb start` dan proses `php -S` background TIDAK bertahan antar-call.
- Jadi: mariadb start + php server start + testing HARUS dalam **satu bash_tool call** yang sama (pakai `&&` atau baris baru, bukan terpisah call).
- PHP butuh extension `php-mysql` (PDO MySQL driver) dan `php-gd` (kalau butuh generate gambar test) — install manual kalau belum ada: `apt-get install -y php-mysql php-gd`

## Riwayat Keputusan Desain Penting

1. **Statistik jadi 5 kotak** (bukan 4): ditambah "Kepala Keluarga". Sempat bug kepotong karena kejepit di kolom kanan (setengah lebar) — solusinya statistik dipindah jadi full-width row di bawah, bukan di dalam kolom teks.
2. **Section "Struktur Pemerintahan"** ditambahkan (di luar desain asli Stitch) — berisi Lurah + 4 Lingkungan (Buntu Batakan, Garampa', Mandetek, Rante To'long) masing-masing dengan daftar RT + no HP. Data ini publik atas persetujuan user (no HP memang dimaksudkan untuk kontak warga).
3. **Nomor HP RT Mendoe Selatan** (Lukas Bara' Padang) diisi `081355104420` — ini hasil TEBAKAN dari tulisan tangan yang kurang jelas di foto sumber asli. User sudah diingatkan untuk cek ulang tapi belum konfirmasi final. **Jangan anggap ini pasti benar.**
4. **Tombol "Explore Village"** (hero) → link ke Google Maps (tab baru). Tombol "Explore Village" di navbar **dihapus** (redundan). Tombol "View Village Profile" → scroll ke `#profil-desa`.
5. **Tombol "Lihat Galeri Lengkap"** dinonaktifkan + label "Segera Hadir" (belum ada halaman galeri terpisah).
6. **Link footer "Pengaduan/Informasi Publik/Surat Online/BUMDes"** dinonaktifkan + "Segera Hadir" (di luar scope).
7. **Nama entitas:** awalnya "Desa Tambunan", user klarifikasi ini sebenarnya **"Kelurahan Tambunan"** — sudah diganti di SELURUH halaman (title, meta, navbar, hero, footer, dashboard, dll). Logo Kabupaten Tana Toraja (background dibikin transparan pakai Python PIL) dipasang di navbar sebelah kiri nama.
8. **Peta di section Kontak**: awalnya Google Maps embed (sempat blank karena masalah jaringan/adblocker), sekarang diganti gambar **Peta Administrasi Kelurahan** (upload user, dikompres dari 3.7MB → ~857KB JPEG). Tombol "Buka di Google Maps" tetap dipertahankan sebagai link terpisah.
9. **Icon sosial media** yang rusak (`face_nod` — bukan icon Material Symbols yang valid) diganti icon yang benar. Link masih placeholder `#`, user belum kasih link asli.
10. **Gambar foto** (hero, profil, potensi, galeri, berita) awalnya semua pakai URL placeholder dari Google Stitch (`lh3.googleusercontent.com/aida-public/...`) — ini akan/sudah mulai digantikan foto asli lewat fitur upload di dashboard.
11. **Perbaikan responsif mobile** (setelah user tes langsung di HP via IP lokal): (a) navbar logo+teks terlalu besar di mobile → dibuat responsif (`text-base sm:text-lg md:text-headline-md`, logo `h-7 w-7 md:h-10 md:w-10`); (b) kotak peta di section Kontak punya `min-h-[500px]` tanpa prefix `lg:` sehingga maksa tinggi 500px di mobile juga → bikin gambar peta kelihatan kecil di tengah kotak kosong raksasa → diperbaiki jadi `aspect-[4/3] lg:aspect-auto lg:min-h-[500px]`; (c) header section Galeri pakai `items-end` yang di mode `flex-col` (mobile) berarti align ke KANAN bukan ke bawah → judul "Jendela Desa" jadi rata kanan bukan kiri → diperbaiki jadi `items-start md:items-end`; (d) `.masonry-grid` di galeri pakai `minmax(300px, 1fr)` yang di layar sempit bikin kolom kedua ke-squeeze jadi sliver tipis → ditambah media query `@media (max-width: 767px)` supaya jadi 1 kolom penuh di mobile. **Pelajaran:** SELALU cross-check kelas Tailwind yang tidak ada prefix breakpoint (`md:`/`lg:`) — kelas tanpa prefix berlaku di SEMUA ukuran layar termasuk mobile, sering jadi sumber bug responsif yang tidak kelihatan kalau cuma preview di desktop.
12. **Ekspansi besar: Fasilitas Umum, Artikel, Legal Corner** (situs berubah dari single-page jadi multi-page). Latar belakang: 2 teman user mau numpang konten di situs ini (1 mau artikel desa, 1 mau "Legal Corner" — portal info hukum multi-halaman kayak buku). Keputusan yang diambil:
    - **Artikel** dibuat TERPISAH dari Berita (Artikel = tulisan panjang/mendalam dengan halaman detail sendiri `artikel-detail.php?id=X`; Berita = kartu ringkas di homepage, tanpa halaman detail).
    - **Legal Corner** dibuat 5 sub-halaman: Informasi Hukum (CRUD biasa), Tanya Hukum (form publik submit pertanyaan → tersimpan ke `legal_faq` dengan status `pending` → admin jawab lewat dashboard → **follow-up ke penanya dilakukan MANUAL oleh admin lewat email pribadi**, BUKAN dikirim otomatis oleh sistem — sengaja dihindari fitur kirim-email-otomatis/SMTP karena kompleks & rawan disalahgunakan; jawaban yang ditandai `tampil_publik=1` akan muncul di FAQ publik halaman `legal-tanya.php`), Rujukan Layanan (CRUD biasa, daftar kontak instansi), Unduh Buku Saku (upload & download file PDF, pakai `handle_pdf_upload()` terpisah dari upload gambar, validasi MIME `application/pdf`, maks 10MB), Kontak Darurat (CRUD biasa, daftar nama+nomor).
    - **Fasilitas Umum**: section baru di homepage (bukan halaman terpisah), ditaruh setelah "Struktur Pemerintahan". Berisi ~23 titik lokasi (kantor kelurahan, sekolah, gereja, dll) dari data peta administrasi. Tiap item: nama + tombol "Maps" yang **aktif** (link ke Google Maps place ID) kalau `gmaps_link` diisi, atau **abu-abu/disabled** (`cursor-not-allowed`) kalau `gmaps_link` NULL (untuk lokasi yang belum ketemu di Google Maps).
    - **Navbar diubah total** jadi struktur dropdown (dari 7 item flat jadi grup): `Home | Profil Desa | Wisata▾ | Layanan▾ | Berita▾ | Kontak`. Dropdown pakai CSS murni (`group` + `group-hover`), TIDAK butuh JS. Mobile nav tetap flat list dengan label grup (`<span>` non-klik) di atas tiap kelompok link, bukan accordion — lebih simpel.
    - **Arsitektur file BARU** (lihat catatan penting di atas soal `index.php` vs `includes/partials/`): karena situs sekarang multi-halaman, dibuat `includes/partials/nav.php` dan `footer.php` supaya 8 halaman baru (artikel, artikel-detail, 5x legal-*, legal-corner) tidak perlu duplikasi ratusan baris HTML. `index.php` SENGAJA TIDAK diubah untuk pakai partial ini (tetap inline) demi menghindari risiko merusak scrollspy/smooth-scroll yang sudah teruji.
    - **Admin dipecah jadi halaman terpisah** (bukan ditumpuk di `dashboard.php` yang sudah 500+ baris): `admin/fasilitas.php`, `admin/artikel.php`, `admin/legal-corner.php` (1 halaman dengan 5 tab JS untuk semua sub-Legal Corner). Semua halaman admin punya nav horizontal di header untuk pindah antar halaman.
    - **Bug yang ditemukan saat testing** (sudah diperbaiki): endpoint `api/legal-tanya-submit.php` pakai fungsi `mb_strlen()` yang butuh PHP extension `mbstring` — di environment testing awalnya belum terinstall, sempat bikin 500 error. Sudah dites ulang setelah extension diinstall dan berfungsi normal. **XAMPP standar sudah include mbstring by default**, jadi kemungkinan besar user tidak akan mengalami ini, tapi tetap dicatat sebagai potential gotcha kalau pindah ke hosting yang mbstring-nya tidak aktif.
    - **Keterbatasan yang diketahui:** scrollspy nav (garis bawah menu ikut section yang di-scroll) sekarang HANYA jalan untuk "Home", "Profil Desa", "Kontak" — link yang sekarang ada di dalam dropdown (Struktur Pemerintahan, Fasilitas Umum, Potensi Desa, Galeri, Berita) kehilangan class `nav-link`+`data-section` sehingga tidak ikut ter-highlight saat section-nya di-scroll. Bukan bug/error (tidak ada JS exception), cuma minor visual imperfection yang belum diperbaiki karena dianggap tidak krusial.
13. **Bug navbar putih di halaman baru** (ditemukan user via screenshot): `js/main.js` punya scroll-listener yang toggle class `bg-transparent` ↔ `bg-primary/95` pada `<header>` — ini didesain KHUSUS untuk `index.php` (nav transparan di atas hero, solid pas discroll). Karena `main.js` di-load bareng di semua halaman baru (lewat `footer.php`), listener ini ikut jalan di halaman yang headernya sudah solid dari awal (`includes/partials/nav.php` pakai `bg-primary` tanpa `bg-transparent`), nambahin class `bg-transparent` yang bentrok/menang di cascade CSS → header keliatan putih/transparan di halaman-halaman baru pas posisi scroll di atas.
    - **User memilih fix ini sendiri** (bukan saya) untuk menghemat token — fix yang saya kasih: tambah guard di awal listener `if (!header || !header.classList.contains("bg-transparent")) return;` di `js/main.js`. **BELUM DIKONFIRMASI user sudah apply atau belum** — kalau nemu bug sama lagi, kemungkinan fix ini belum di-apply, tinggal cek `js/main.js` baris ~62.
14. **Background foto di header 7 sub-halaman** (Legal Corner, Artikel, dan 5 halaman legal-* lainnya): awalnya solid hijau polos (`bg-primary`), user bilang "terlalu ijo tok". Diperbaiki dengan menambah foto Tongkonan (reuse foto yang sama dengan kartu "Warisan Budaya" di Potensi Desa — sengaja reuse aset yang sudah ada, tidak upload baru) sebagai background dengan overlay gradient gelap (`bg-gradient-to-b from-primary/95 via-primary/90 to-primary`, foto sendiri `opacity-25`) supaya teks tetap kebaca. Pola HTML: `<section class="relative ... overflow-hidden">` berisi `<div class="absolute inset-0 z-0">` (foto+overlay) lalu `<div class="relative z-10 ...">` (konten asli, tidak diubah). Diterapkan otomatis lewat script Python `re.sub` ke 7 file sekaligus (`legal-buku.php`, `legal-darurat.php`, `legal-informasi.php`, `legal-rujukan.php`, `legal-tanya.php`, `legal-corner.php`, `artikel.php`) karena pola HTML-nya identik persis. **Kalau nanti nambah halaman baru dengan pola header sama, ikuti pola ini juga biar konsisten.**
15. **Hosting-readiness hardening** (user tanya "apakah zip ini sudah hostingable"): ditambahkan 4 hal supaya lebih siap dipakai di hosting sungguhan (bukan cuma XAMPP lokal):
    - `error_reporting(E_ALL)` + `ini_set('display_errors', '0')` + `ini_set('log_errors', '1')` di `includes/db.php` (dijalankan di awal karena file ini di-require paling pertama oleh hampir semua halaman) — supaya error PHP tidak tampil mentah ke pengunjung publik (risiko keamanan/informasi), tapi tetap tercatat di log server untuk debugging.
    - `session_set_cookie_params()` di `includes/auth.php` sekarang deteksi HTTPS otomatis (`$_SERVER['HTTPS']` atau header `X-Forwarded-Proto`) dan set `'secure' => true` kalau situs diakses via HTTPS — proteksi tambahan supaya cookie session admin tidak bisa dicuri lewat koneksi HTTP biasa.
    - 3 file `.htaccess` baru: (1) root project — matikan directory listing (`Options -Indexes`), blokir akses browser langsung ke file `.sql/.md/.log/.env/.ini`; (2) `includes/.htaccess` — blokir total akses URL langsung ke folder ini (`Require all denied`), karena isinya cuma boleh diakses lewat `require`/`include` dari PHP lain, bukan dibuka langsung di browser; (3) `sql/.htaccess` — sama, blokir total, karena isinya struktur database yang seharusnya tidak pernah bisa diakses publik.
    - **Semua diuji dengan Apache sungguhan** (bukan cuma PHP built-in server) — install `apache2` + `libapache2-mod-php8.3` di sandbox testing, arahkan DocumentRoot ke folder project, aktifkan `AllowOverride All` (supaya `.htaccess` benar-benar dibaca), lalu tes: akses biasa (200 OK), akses langsung ke `includes/db.php`/`sql/schema.sql`/`*.md`/directory listing (semua correctly 403), dan alur login+dashboard tetap jalan normal dengan cookie session benar (`HttpOnly` ada, `secure` sesuai kondisi HTTP/HTTPS).
    - **Yang MASIH perlu dicek user sendiri saat upload ke hosting asli:** batas `upload_max_filesize`/`post_max_size` PHP hosting (banyak shared hosting default cuma 2MB, padahal upload foto/PDF butuh 5-10MB) — sudah dicatat di README sebagai checklist go-live.
16. **Link "Login Admin" ditambahkan ke footer** — di `index.php` ditaruh sejajar dengan link "Kebijakan Privasi/Syarat & Ketentuan/Peta Situs" (baris kecil di footer kiri bawah); di `includes/partials/footer.php` (dipakai 8 halaman lain yang TIDAK punya baris privacy-links itu) ditaruh di baris copyright bagian bawah, sejajar dengan teks "© 2024 Pemerintah Kelurahan Tambunan". Styling sengaja dibuat lebih redup (`opacity-40/50`) dibanding link lain karena ini bukan link untuk pengunjung umum, cuma buat Pak Lurah — tidak perlu menonjol tapi tetap gampang ditemukan kalau dicari.

## Yang BELUM Selesai / Belum Diminta Eksplisit

- Fitur "Ganti Username & Password" di dashboard — user **menolak** ini, memilih cara manual lewat phpMyAdmin
- Logo kecamatan & Peta Administrasi belum bisa diganti dari Dashboard (masih manual replace file) — sudah ditawarkan ke user, belum ada jawaban final
- Section "Profil Desa" nav & beberapa label section masih pakai kata generik "Desa" (misal id section `profil-desa`, `potensi-desa` — ini nama teknis/ID, TIDAK perlu diganti karena bukan teks yang tampil ke user)
- Belum ada halaman detail/single untuk artikel Berita (klik artikel belum ke mana-mana) — belum diminta
- Belum ada resize/compress otomatis untuk foto yang diupload lewat dashboard (masih upload apa adanya, maks 5MB) — user sudah diedukasi soal Squoosh/kompresi manual sebelum upload

## Status Testing

Semua fitur CRUD (Statistik, Lurah, Lingkungan+RT, Hero, Profil, Potensi, Galeri, Berita) sudah:
- ✅ Lolos `php -l` syntax check
- ✅ Diverifikasi keseimbangan tag HTML
- ✅ Dites end-to-end dengan MySQL + PHP built-in server sungguhan (login, upload foto asli, tambah/edit/hapus, cek tampil di halaman publik)
- ✅ Database di-reset ke data bersih sebelum file final dikirim ke user

## File yang Dikirim ke User

1. `desa-tambunan.zip` — project lengkap siap upload ke XAMPP/hosting
2. `preview-offline.zip` — snapshot statis `index.php` (HTML hasil render, bisa dibuka langsung tanpa server) buat preview cepat tanpa perlu XAMPP nyala. **Perlu di-regenerate ulang setiap ada perubahan konten/desain** (caranya: jalankan PHP server, curl `index.php`, simpan hasilnya sebagai `preview.html`, copy folder `css/`, `js/`, `assets/` ke sebelahnya).

---

*Dokumen ini dibuat 27 Juli 2026 oleh Claude (Anthropic) atas permintaan user, untuk menjaga kontinuitas kerja kalau user pindah ke AI/tool lain.*
