# Website Kelurahan Tambunan — Panduan Setup

## Struktur Folder
```
desa-tambunan/
├── index.php                  ← Halaman utama (publik)
├── css/style.css
├── js/{tailwind-config.js, main.js}
├── admin/
│   ├── login.php               ← Halaman login admin
│   ├── dashboard.php           ← Halaman CRUD (setelah login)
│   └── dashboard.js
├── api/                        ← Endpoint backend (jangan dihapus)
├── includes/
│   ├── db.php                  ← ⚠️ WAJIB DIEDIT (kredensial database)
│   └── auth.php
├── sql/schema.sql              ← Import ini ke database
└── assets/images/              ← Taruh foto asli desa di sini
```

## Langkah Setup di Hosting (cPanel)

**1. Buat database MySQL**
Masuk cPanel → **MySQL Databases** → buat database baru (misal `namauser_desatambunan`) dan user database baru, lalu hubungkan (assign) user itu ke database dengan **ALL PRIVILEGES**. Catat 3 hal ini: nama database, username, password.

**2. Import struktur tabel**
Masuk **phpMyAdmin** → pilih database yang baru dibuat → tab **Import** → upload file `sql/schema.sql` → klik Go.

**2b. Import tabel tambahan (Hero, Potensi Desa, Galeri)**
Masih di database yang sama → tab **Import** lagi → upload file `sql/migration_content.sql` → klik Go.
*(File ini aman diimport kapan saja, tidak akan menghapus data yang sudah kamu ubah sebelumnya — cuma menambah tabel baru.)*

**3. Edit kredensial database**
Buka file `includes/db.php`, ganti 4 baris ini sesuai punya kamu:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'namauser_desatambunan');   // ganti
define('DB_USER', 'namauser_dbuser');          // ganti
define('DB_PASS', 'password_database_kamu');   // ganti
```

**4. Upload semua file**
Upload seluruh isi folder ini ke `public_html` (atau subfolder domain kamu) via File Manager cPanel atau FTP.

**5. Login pertama kali & GANTI PASSWORD**
Buka `namadomain.com/admin/login.php`
- Username: `admin`
- Password: `GantiSegera123!`

⚠️ **PENTING**: password ini contoh/default dan HARUS diganti. Cara ganti password saat ini belum ada tombolnya di dashboard (di luar scope awal) — untuk sementara, ganti manual lewat phpMyAdmin:
1. Generate hash password baru dengan menjalankan file kecil ini sekali lewat browser lalu **hapus filenya setelah dipakai**:
   ```php
   <?php echo password_hash('PASSWORD_BARU_KAMU', PASSWORD_DEFAULT);
   ```
2. Copy hasilnya, buka phpMyAdmin → tabel `admins` → edit baris `admin` → paste ke kolom `password_hash`.

## Testing Lokal (opsional, sebelum upload ke hosting)
Kalau mau coba dulu di laptop sebelum upload, install **Laragon** (Windows) atau **XAMPP** (Windows/Mac), taruh folder ini di `www`/`htdocs`, buat database lewat phpMyAdmin bawaan, import `schema.sql`, edit `includes/db.php` (biasanya `DB_USER` = `root`, `DB_PASS` = kosong `''`), lalu buka `localhost/desa-tambunan/`.

## ⚠️ Checklist SEBELUM Website Diakses Publik (Go-Live)

Selain langkah setup di atas, sebelum website ini benar-benar online diakses masyarakat, **wajib** dicek:

- [ ] Password admin sudah diganti dari default `GantiSegera123!` (lihat README lama / minta bantuan generate hash bcrypt)
- [ ] `includes/db.php` sudah diisi kredensial database hosting yang sebenarnya (bukan `root`/kosong)
- [ ] File `.htaccess` (di folder utama, `includes/`, dan `sql/`) ikut ter-upload — kadang file yang diawali titik tersembunyi secara default di File Manager, aktifkan "Show Hidden Files"
- [ ] Cek batas upload PHP di hosting (`upload_max_filesize` & `post_max_size`) minimal 10MB — banyak hosting defaultnya cuma 2MB, kalau kurang, upload foto/PDF di dashboard akan gagal
- [ ] Ganti link sosial media di footer (masih placeholder `#`)
- [ ] Cek ulang nomor HP RT Mendoe Selatan (Lukas Bara' Padang) — sempat tidak yakin waktu input awal

## Catatan
- **Foto Hero, Potensi Desa, dan Galeri sekarang bisa diganti langsung dari dashboard admin** — tinggal login, upload foto baru (JPG/PNG/WEBP, maks 5MB), otomatis tersimpan ke folder `assets/images/` dan tampil di halaman utama.
- Link sosial media di footer masih placeholder (`#`) — ganti dengan link asli.
- Nomor HP RT Mendoe Selatan (Lukas Bara' Padang) di `sql/schema.sql` diisi berdasarkan tulisan tangan yang kurang jelas di foto sumber — **tolong dicek ulang & dikoreksi lewat dashboard admin** kalau salah.
