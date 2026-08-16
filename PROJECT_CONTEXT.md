# PROJECT CONTEXT — Sistem Informasi Penjualan Produk Mesin
## CV Adzra Engineering Bandung

> File ini berisi konteks lengkap project untuk digunakan oleh AI coding assistant (Copilot/Cursor/Claude di VSCode dsb). Bacalah seluruh isi file ini sebelum mulai membuat kode agar struktur, fitur, dan gaya desain konsisten dari awal hingga akhir pengembangan.

---

## 1. Ringkasan Project

Aplikasi web untuk **CV Adzra Engineering Bandung**, perusahaan yang bergerak di bidang jasa dan fabrikasi mesin industri (Rotary Dryer, mesin cetak Wood Pellet, mesin cetak Pelet, dsb). Saat ini perusahaan mempromosikan produk lewat media sosial dan melayani konsultasi/pemesanan lewat WhatsApp secara manual, sementara pencatatan transaksi masih menggunakan Microsoft Excel.

Tujuan aplikasi:
- Menyediakan **katalog produk mesin terpusat** (deskripsi, spesifikasi, gambar, video) yang bisa diakses pelanggan lewat website.
- Menyediakan **tombol integrasi WhatsApp** pada halaman detail produk agar pelanggan bisa langsung konsultasi/pemesanan dengan informasi produk yang sudah terbawa otomatis di pesan.
- Menyediakan **panel admin** untuk mengelola produk, pelanggan, pemesanan, transaksi, dan laporan penjualan — menggantikan pencatatan manual di Excel.

> ⚠️ **Catatan penting:** Integrasi WhatsApp di sini **BUKAN** WhatsApp Business API/gateway. Cukup tombol/link `https://wa.me/<nomor>?text=<pesan otomatis>` yang mengarahkan pelanggan ke aplikasi WhatsApp untuk melanjutkan chat manual dengan admin.
>
> ⚠️ **Catatan stack:** Project ini pakai **Laravel murni** (tanpa Breeze/Jetstream/starter kit apapun) dan **tanpa JS framework** (tanpa Alpine/Vue/React). Semua interaksi dinamis di frontend cukup pakai vanilla JavaScript.

---

## 2. Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 13 (PHP 8.3), pola MVC — **Laravel murni, tanpa starter kit** |
| Auth | Dibuat manual (bukan Breeze/Jetstream/Fortify) |
| Frontend | Blade + Tailwind CSS v4 |
| Interaktivitas | Vanilla JavaScript saja (tanpa Alpine.js / framework JS apapun) |
| Database | MySQL |
| Asset build | Vite (default Laravel 13) |

> ⚠️ **Penting:** Jangan install `laravel/breeze` atau starter kit auth apapun. Semua fitur login/register/logout admin dibuat manual (route, controller, form Blade, validasi, hashing password) langsung dari Laravel murni. Ini disengaja supaya struktur project sederhana dan mudah dipahami tanpa dependency tambahan.

Gunakan konvensi standar Laravel: Eloquent ORM, Form Request untuk validasi, Resource Controller, Migration & Seeder untuk skema database, Middleware `auth` bawaan Laravel untuk proteksi route admin.

---

## 3. Desain / Design System

Tema: **modern, bersih, industrial-professional** — mencerminkan logo perusahaan (biru-putih) dengan aksen kuning/amber ala industri mesin.

### Palet Warna

| Peran | Warna | Hex |
|---|---|---|
| Primary (dominan) | Biru | `#1D4ED8` (blue-700) |
| Primary Dark (header/footer/hover) | Biru tua | `#1E3A8A` (blue-900) |
| Primary Light (highlight ringan) | Biru muda | `#3B82F6` (blue-500) |
| Background utama | Putih | `#FFFFFF` |
| Background alternatif (section) | Abu sangat terang | `#F8FAFC` (slate-50) |
| Aksen / CTA (tombol WA, "Pesan Sekarang") | Kuning/Amber | `#F59E0B` (amber-500) |
| Aksen hover | Amber gelap | `#D97706` (amber-600) |
| Teks utama | Abu gelap | `#1E293B` (slate-800) |
| Teks sekunder | Abu netral | `#64748B` (slate-500) |
| Border/divider | Abu terang | `#E2E8F0` (slate-200) |

Aturan pakai:
- Biru dominan untuk navbar, footer, heading section, badge kategori.
- Putih/abu terang untuk background, supaya terasa lega dan modern (banyak whitespace).
- Kuning/amber **hanya untuk elemen aksi penting** (tombol WhatsApp, CTA "Konsultasi Sekarang", badge "Produk Unggulan") — jangan dipakai besar-besaran supaya tetap terlihat premium, bukan norak.

### Tipografi
- Font heading: `Poppins` (600–700) — tegas, modern.
- Font body: `Inter` atau `Plus Jakarta Sans` — mudah dibaca.
- Import via Google Fonts di layout utama.

### Gaya UI
- Card dengan `rounded-xl`, `shadow-sm` → `shadow-md` saat hover.
- Banyak whitespace, grid layout rapi (grid produk 3 kolom desktop, 1 kolom mobile).
- Tombol WhatsApp pakai warna amber + icon WhatsApp, posisi sticky/floating di halaman detail produk (opsional).
- Navbar sticky, transparan di atas hero lalu solid saat scroll (opsional, nice-to-have).

---

## 4. Skema Database (MVP)

```
users                     -- akun admin/staff (dibuat manual, tanpa starter kit)
├─ id, name, email, password (hashed), role (enum string: admin | staff, default: admin), timestamps

categories                -- kategori produk (type=produk) & layanan (type=layanan)
├─ id, name, slug, type (string: produk | layanan, default produk), timestamps

products                  -- produk mesin & sparepart
├─ id, category_id (FK), name, slug, description, price (nullable),
│  warranty_months (nullable), stock (unsigned integer, default 0),
│  is_featured (boolean, default false), timestamps

product_specifications     -- spesifikasi teknis per produk (key-value, banyak baris per produk)
├─ id, product_id (FK), spec_key, spec_value, timestamps

product_images             -- galeri gambar produk (banyak gambar per produk)
├─ id, product_id (FK), image_path, is_primary (boolean), timestamps

product_videos              -- video produk (link YouTube/embed atau file upload)
├─ id, product_id (FK), video_url, caption (nullable), timestamps

customers                  -- data pelanggan (diinput admin saat ada pesanan masuk dari WA)
├─ id, name, phone, email (nullable), address (nullable), timestamps

services                   -- layanan jasa (Custom Mesin, Service, Instalasi Listrik, Panel)
├─ id, category_id (FK categories type=layanan, nullable), name, slug (unique),
│  description (nullable), price (nullable), is_featured (boolean, default false), timestamps

suppliers                  -- pemasok material/suku cadang
├─ id, name, contact_name (nullable), phone (nullable), email (nullable), address (nullable), timestamps

orders                     -- pemesanan (item: produk ATAU layanan)
├─ id, customer_id (FK), product_id (FK, nullable), service_id (FK services, nullable),
│  quantity, notes (nullable),
│  status (enum: pending, diproses, selesai, batal),
│  admin_user_id (FK users, nullable), total (decimal, nullable), warranty_end_date (date, nullable),
│  timestamps

transactions                -- transaksi penjualan
├─ id, order_id (FK), staff_user_id (FK users, nullable), amount,
│  payment_type (string, nullable: transfer/tunai/lainnya), transaction_date,
│  status (enum: lunas, belum_lunas), timestamps

cashbooks                   -- buku kas (pemasukan/pengeluaran)
├─ id, type (enum: pemasukan, pengeluaran), amount, description (nullable),
│  transaction_date, user_id (FK users, nullable), timestamps

workers                     -- pekerja / tenaga produksi
├─ id, name, position (nullable), phone (nullable), salary (decimal, nullable, upah harian), timestamps

attendances                 -- absensi pekerja
├─ id, worker_id (FK), date, check_in (time, nullable), check_out (time, nullable),
│  timestamps, unique(worker_id, date)

payrolls                    -- penggajian (draft → approved; approve otomatis mencatat cashbook pengeluaran)
├─ id, worker_id (FK), period (string Y-m), total_days, salary_amount (decimal),
│  status (string: draft | approved), approved_by (FK users, nullable), approved_at (timestamp, nullable),
│  timestamps, unique(worker_id, period)
```

> Laporan penjualan **tidak perlu tabel terpisah** — cukup query agregasi dari tabel `transactions` (filter per tanggal/bulan, total pendapatan, jumlah transaksi).

---

## 5. Scope MVP (Prioritas Pengembangan Awal)

Fokus dulu ke kerangka aplikasi yang berjalan penuh (end-to-end), fitur-fitur tambahan bisa menyusul.

### Sisi Publik (Pelanggan)
- [x] Landing page (hero, ringkasan perusahaan, produk unggulan, CTA)
- [x] Halaman katalog produk (list + filter kategori + search sederhana)
- [x] Halaman detail produk (deskripsi, spesifikasi, galeri gambar, video, tombol WhatsApp dengan pesan otomatis berisi nama produk)
- [x] Halaman tentang perusahaan
- [x] Halaman katalog layanan (/layanan, CTA WhatsApp per layanan)

### Sisi Admin (auth manual, layout sidebar + topbar)
- [x] Dashboard ringkas (card Total Penjualan, Stok Kritis, Saldo Kas Saat Ini + total produk/kategori/pelanggan/pemesanan)
- [x] CRUD Kategori (Produk & Layanan via tipe)
- [x] CRUD Produk (upload gambar, spesifikasi, video, lama garansi, stok)
- [x] Kelola Data Pelanggan
- [x] Kelola Pemesanan (buat pesanan: pilih pelanggan + produk/layanan + total harga + selesai garansi; ubah status)
- [x] Kelola Transaksi (tab Pelanggan/Pemesanan/Transaksi di /admin/transaksi)
- [x] Cek Garansi (search nama pelanggan/no. pesanan → status Aktif/Kedaluwarsa dari warranty_end_date)
- [x] Buku Kas Utama (filter jenis + rentang tanggal, ringkasan pemasukan/pengeluaran/saldo)
- [x] Laporan multi-tab (Penjualan | Stok | Arus Kas | Penggajian, tabel Tailwind)
- [x] CRUD Supplier
- [x] Kelola Akun (tambah/edit admin & staff)
- [x] Rekap Absensi (read-only, filter bulan)
- [x] Penggajian (generate dari data absensi × upah harian, approve → otomatis catat cashbook pengeluaran)

### Sisi Staff Operasional (role=staff, middleware `role:staff`, prefix `/staff`)
- [x] Dashboard Operasional (jumlah pesanan pending/diproses, hadir hari ini, stok kritis, transaksi terbaru)
- [x] Kasir: input pembayaran (DP/Termin/Lunas, tunai/transfer/lainnya) → otomatis catat Buku Kas pemasukan; status Lunas otomatis menyelesaikan pesanan; faktur/kwitansi printable (`#TRX-{id}`)
- [x] Kasir: catat pengeluaran operasional → Buku Kas pengeluaran
- [x] Operasional: update progress pesanan (status + catatan) & kelola stok (tambah/kurang, tidak bisa minus)
- [x] HR Harian: CRUD data pekerja (upah harian) & input absensi harian (jam masuk/keluar, tolak duplikat, tolak jam keluar < jam masuk)

### Di luar MVP (nanti menyusul, jangan dikerjakan dulu)
- Export laporan ke PDF/Excel
- Role & permission granular di atas admin/staff
- Notifikasi email
- Rating/review produk
- Multi-bahasa
- CRUD admin untuk services (katalog layanan saat ini masih read-only via seeder)

---

## 6. Struktur Routing (acuan awal)

```
# Public
GET  /                      -> HomeController@index
GET  /produk                -> ProductController@index (katalog mesin & sparepart + filter)
GET  /produk/{slug}         -> ProductController@show (detail)
GET  /layanan               -> ServiceController@index (katalog layanan + CTA WhatsApp)

# Auth (dibuat manual)
GET  /login                 -> AuthController@showLoginForm
POST /login                 -> AuthController@login
POST /logout                -> AuthController@logout
(tanpa halaman register publik — akun admin dibuat lewat seeder/tinker, bukan self-register)

# Admin (middleware: auth + role:admin)
GET  /admin/dashboard       -> Admin\DashboardController@index
Resource: /admin/kategori   -> Admin\CategoryController (tab type=produk|layanan)
Resource: /admin/produk     -> Admin\ProductController
Resource: /admin/pelanggan  -> Admin\CustomerController
Resource: /admin/pemesanan  -> Admin\OrderController
Resource: /admin/transaksi  -> Admin\TransactionController
GET  /admin/laporan         -> Admin\ReportController@index (tab=penjualan|stok|kas|penggajian)
GET  /admin/garansi         -> Admin\WarrantyController@index (cek garansi, ?q=nama/id)
GET  /admin/kas             -> Admin\CashbookController@index (buku kas, ?type=&from=&to=)
Resource: /admin/supplier   -> Admin\SupplierController
Resource: /admin/akun       -> Admin\UserController (names: users, param: user)
GET  /admin/absensi         -> Admin\AttendanceController@index (read-only, ?month=Y-m)
GET  /admin/penggajian      -> Admin\PayrollController@index (?period=Y-m)
POST /admin/penggajian/generate -> Admin\PayrollController@generate
POST /admin/penggajian/{payroll}/approve -> Admin\PayrollController@approve (auto cashbook)
DELETE /admin/penggajian/{payroll} -> Admin\PayrollController@destroy (hanya draft)

# Staff Operasional (middleware: auth + role:staff)
GET  /staff/dashboard       -> Staff\DashboardController@index
GET  /staff/transaksi       -> Staff\TransactionController@index (daftar faktur, ?order_id=&status=)
GET  /staff/transaksi/create -> Staff\TransactionController@create (?order_id= preselect)
POST /staff/transaksi       -> Staff\TransactionController@store (transaksi + cashbook pemasukan; lunas -> order selesai)
GET  /staff/transaksi/{transaksi}/invoice -> Staff\TransactionController@invoice (kwitansi, print:hidden)
GET  /staff/kas/create      -> Staff\CashbookController@create
POST /staff/kas             -> Staff\CashbookController@store (pengeluaran)
GET  /staff/pemesanan       -> Staff\OrderController@index (?status=&q=)
GET  /staff/pemesanan/{pemesanan}/edit -> Staff\OrderController@edit
PUT  /staff/pemesanan/{pemesanan}      -> Staff\OrderController@update (status + notes)
GET  /staff/stok            -> Staff\StockController@index (?q=)
POST /staff/stok            -> Staff\StockController@update (tambah/kurang)
Resource: /staff/pekerja    -> Staff\WorkerController (names: workers, param: pekerja)
GET  /staff/absensi         -> Staff\AttendanceController@index (?date=)
POST /staff/absensi         -> Staff\AttendanceController@store (tolak duplikat & check_out < check_in)
```

---

## 7. Kebutuhan Nonfungsional

- Responsive (mobile-first), minimal breakpoint: mobile, tablet, desktop.
- Validasi input pakai Form Request Laravel.
- Gambar produk disimpan di `storage/app/public`, jangan lupa `php artisan storage:link`.
- Gunakan `slug` (bukan id) di URL produk untuk SEO-friendly.
- Kode rapi mengikuti konvensi Laravel (PSR-12), pisahkan logic ke Controller/Service, jangan taruh query kompleks di Blade.

---

## 8. Catatan untuk AI Assistant di VSCode

- Mulai dari `composer create-project laravel/laravel` versi 13 (PHP 8.3). **Jangan** install starter kit apapun (Breeze/Jetstream/Fortify).
- Install Tailwind CSS v4 secara manual lewat Vite (`npm install tailwindcss @tailwindcss/vite`), bukan lewat scaffolding starter kit.
- Buat sistem auth admin manual:
  - Migration `users` (pakai bawaan Laravel, cukup tambah kolom `role` bila perlu).
  - `AuthController` dengan method `showLoginForm`, `login` (pakai `Auth::attempt`), `logout`.
  - Middleware `auth` bawaan Laravel untuk proteksi semua route `/admin/*`.
  - **Role middleware**: `App\Http\Middleware\EnsureUserHasRole` (alias `role`, didaftarkan di `bootstrap/app.php`). Grup `/admin/*` memakai `role:admin`, grup `/staff/*` memakai `role:staff`. Jika role salah, staff diarahkan ke `staff.dashboard`, lainnya ke `admin.dashboard` (dengan flash error). Login diarahkan ke dashboard sesuai role.
  - Buat admin awal lewat `database/seeders/AdminSeeder.php`, bukan lewat form register publik.
- Setelah scaffolding dasar jalan (migration, seeder, admin bisa login), baru bangun fitur MVP satu per satu sesuai urutan di Bagian 5.
- Buat migration dan model dulu sebelum controller & view.
- Setup `tailwind.config.js` (atau konfigurasi CSS-based Tailwind v4 lewat `@theme` di file CSS utama) dengan warna custom sesuai palet di Bagian 3 (misalnya `colors.brand.primary`, `colors.brand.accent`).
- Untuk interaksi ringan di frontend (misalnya toggle menu mobile, modal galeri gambar), gunakan **vanilla JavaScript murni** — jangan tambahkan Alpine.js, Livewire, atau library JS lain kecuali diminta.
- Untuk tombol WhatsApp, format link: `https://wa.me/62XXXXXXXXXX?text=` + `urlencode("Halo, saya tertarik dengan produk {nama_produk}, mohon info lebih lanjut.")`
