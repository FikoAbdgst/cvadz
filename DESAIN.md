# DESAIN.md — Sistem Desain CV Adzra Engineering

> Dokumen ini adalah rujukan visual tunggal untuk seluruh aplikasi. Sebelum membuat halaman/komponen baru, baca dulu bagian **Arah Desain** dan **Elemen Signature**, lalu ikuti token & pola komponen di bawahnya secara konsisten. Jangan menyimpang dari palet/tipografi yang ditentukan di sini tanpa alasan kuat.

---

## 0. Kenapa dokumen ini dibuat

Homepage yang sudah ada (`resources/views/home.blade.php`) secara fungsi sudah benar, tapi secara visual masih terasa seperti template SaaS generik: hero gradasi biru penuh layar + badge pil + dua tombol, list checklist bulat hijau/biru, kartu statistik putih rounded-shadow. Pola ini bisa dipakai untuk brand apa saja — fintech, agensi, aplikasi kasir — tidak ada yang secara spesifik bilang "ini perusahaan fabrikasi mesin industri". Dokumen ini memperbaiki itu dengan arah desain yang digali dari dunia CV Adzra Engineering sendiri: bengkel, pelat baja, gambar teknik, dan name plate mesin.

---

## 1. Arah Desain

**Konsep: "Spec Sheet / Nameplate Industrial"**

CV Adzra Engineering membuat mesin — rotary dryer, mesin cetak wood pellet, mesin cetak pelet. Dunia mereka penuh dengan hal-hal yang punya bentuk visual khas: **name plate mesin** (pelat logam kecil berisi kode/spesifikasi yang dipasang di badan mesin), **gambar teknik/blueprint** (garis tipis, grid, crop mark di sudut kertas gambar), dan **datasheet** (tabel key–value: tegangan, kapasitas, dimensi).

Alih-alih tema "korporat biru gradasi" yang bisa dipakai brand apapun, seluruh UI aplikasi ini meniru **kertas gambar teknik dan pelat data mesin**:
- Background didasarkan pada warna kertas blueprint pucat, bukan putih polos atau krem hangat.
- Kartu/panel penting diberi bingkai tipis dengan **siku penanda di 4 sudut** (seperti crop mark di kertas gambar teknik / sudut viewfinder kamera) — ini elemen signature aplikasi ini, dipakai berulang di hero, kartu produk, dan tabel spesifikasi.
- Label kecil (eyebrow, kode produk, kategori) ditulis pakai font monospace dengan tracking lebar, meniru cara data ditulis di name plate mesin (`NO. 001 — ROTARY DRYER`), bukan badge pil warna-warni.
- Sudut membulat dijaga kecil/tegas (bukan `rounded-2xl` besar bergaya app mobile) karena logam dan gambar teknik itu presisi, bukan lembut.
- Warna aksen amber dipakai setipis mungkin dan hanya untuk hal yang benar-benar butuh perhatian (CTA utama, garis siku penanda) — meniru cara warna kuning/oranye dipakai di dunia industri: sebagai penanda perhatian/safety, bukan dekorasi.

---

## 2. Token Desain

### 2.1 Warna

| Nama Token | Hex | Peran |
|---|---|---|
| `steel-900` (brand-dark) | `#0F2A42` | Navbar, footer, panel hero gelap |
| `steel-700` (brand-primary) | `#1C4C78` | Tombol/link primer, ikon, judul kategori |
| `steel-400` (brand-primary-light) | `#5C86AC` | Hover state, elemen sekunder |
| `paper-100` (brand-bg) | `#F2F5F7` | Background halaman — "kertas blueprint" |
| `white` (brand-surface) | `#FFFFFF` | Background kartu/panel |
| `graphite-900` (brand-ink) | `#1B222B` | Teks utama |
| `graphite-500` (brand-muted) | `#5C6773` | Teks sekunder/caption |
| `line-200` (brand-border) | `#D7E0E6` | Garis tipis/divider/hairline border |
| `amber-600` (brand-accent) | `#D98A2B` | CTA utama, garis siku penanda, badge kode |
| `amber-700` (brand-accent-dark) | `#B26F1E` | Hover state untuk elemen amber |

Catatan penting:
- Ini **bukan** biru default Tailwind (`blue-700`/`amber-500`). Nilai di atas sengaja lebih desaturated/gelap supaya terasa seperti cat pelat logam, bukan biru "app fintech".
- Amber **maksimal dipakai untuk 1–2 elemen per layar** (CTA + siku penanda). Kalau semua tombol dan badge memakai amber, kesan "penanda perhatian" hilang.

### 2.2 Tipografi

| Peran | Font | Berat | Pemakaian |
|---|---|---|---|
| Display/Heading | `Space Grotesk` | 600–700 | H1–H3, angka besar, nama produk |
| Body | `IBM Plex Sans` | 400–500 | Paragraf, teks UI umum |
| Data/Mono | `IBM Plex Mono` | 500 | Eyebrow label, kode produk, key–value spesifikasi, angka statistik, nav label |

Import di layout utama via Google Fonts:
```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
```

Aturan pakai font mono — ini bagian dari identitas, bukan hiasan:
- Semua eyebrow/label kecil ditulis **UPPERCASE + tracking lebar** pakai mono. Contoh: `NO. 003 / ROTARY DRYER`, `KATEGORI MESIN`, `HUBUNGI KAMI`.
- Tabel spesifikasi produk (key–value) wajib pakai mono untuk kolom key dan value — ini yang bikin halaman detail produk terasa seperti benar-benar baca datasheet mesin, bukan daftar bullet biasa.

### 2.3 Radius & Border

- Radius default: `rounded-sm` (2px) atau `rounded` (4px) saja. **Jangan** pakai `rounded-xl`/`rounded-2xl` untuk kartu/tombol — itu ciri khas template SaaS lembut yang justru ingin kita hindari.
- Panel/kartu penting: border `1px solid` warna `line-200`, tanpa shadow besar. Shadow hanya `shadow-sm` tipis saat hover, bukan `shadow-md`/`shadow-lg` default.
- Semua kartu penting punya **frame siku 4 sudut** (lihat Bagian 3) — ini menggantikan peran shadow sebagai penanda "ini elemen penting".

### 2.4 Motion

- Minim animasi. Hanya transisi warna/border (150–200ms) saat hover — tidak ada efek bounce, float, atau parallax berlebihan.
- Hormati `prefers-reduced-motion`.

---

## 3. Elemen Signature: Corner Bracket ("Siku Penanda")

Ini adalah elemen visual yang berulang di seluruh aplikasi dan menjadi identitas paling mudah dikenali dari desain ini — meniru garis siku di sudut kertas gambar teknik.

### Tambahkan ke `resources/css/app.css` (Tailwind v4, CSS-first config):

```css
@import "tailwindcss";

@theme {
  --color-steel-900: #0F2A42;
  --color-steel-700: #1C4C78;
  --color-steel-400: #5C86AC;
  --color-paper-100: #F2F5F7;
  --color-graphite-900: #1B222B;
  --color-graphite-500: #5C6773;
  --color-line-200: #D7E0E6;
  --color-amber-600: #D98A2B;
  --color-amber-700: #B26F1E;

  --font-display: "Space Grotesk", sans-serif;
  --font-body: "IBM Plex Sans", sans-serif;
  --font-mono: "IBM Plex Mono", monospace;
}

@layer components {
  /* Panel dengan frame siku 4 sudut — elemen signature */
  .plate {
    position: relative;
    border: 1px solid var(--color-line-200);
    background: white;
  }
  .plate::before,
  .plate::after,
  .plate > .plate-corner-br,
  .plate > .plate-corner-bl {
    content: "";
    position: absolute;
    width: 14px;
    height: 14px;
    border-color: var(--color-amber-600);
    transition: border-color 150ms ease;
  }
  .plate::before {
    top: -1px;
    left: -1px;
    border-top: 2px solid;
    border-left: 2px solid;
  }
  .plate::after {
    top: -1px;
    right: -1px;
    border-top: 2px solid;
    border-right: 2px solid;
  }
  .plate-corner-bl {
    bottom: -1px;
    left: -1px;
    border-bottom: 2px solid var(--color-amber-600);
    border-left: 2px solid var(--color-amber-600);
  }
  .plate-corner-br {
    bottom: -1px;
    right: -1px;
    border-bottom: 2px solid var(--color-amber-600);
    border-right: 2px solid var(--color-amber-600);
  }
  .plate:hover::before,
  .plate:hover::after,
  .plate:hover .plate-corner-bl,
  .plate:hover .plate-corner-br {
    border-color: var(--color-steel-700);
  }

  /* Label mono uppercase — dipakai untuk eyebrow, kode produk, kategori */
  .label-mono {
    font-family: var(--font-mono);
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--color-graphite-500);
  }
}
```

### Cara pakai di Blade (butuh 2 `<span>` tambahan untuk sudut bawah karena `::before`/`::after` sudah dipakai sudut atas):

```html
<div class="plate rounded p-6">
    <span class="plate-corner-bl"></span>
    <span class="plate-corner-br"></span>
    <!-- konten kartu -->
</div>
```

Pakai `.plate` untuk: kartu produk, panel hero (di atas background gelap, pakai varian putih transparan), tabel spesifikasi, dan card statistik. **Jangan** pakai di elemen kecil seperti tombol — cukup di panel/kartu berukuran cukup besar supaya siku terlihat jelas, bukan berantakan.

---

## 4. Pola Komponen

### 4.1 Hero

❌ Hindari: gradasi penuh layar + badge pil + headline center + 2 tombol rounded-xl (pola lama).

✅ Ganti dengan: panel gelap `steel-900` dengan **grid garis tipis** sebagai tekstur latar (meniru kertas milimeter blok gambar teknik), teks rata kiri, eyebrow mono berisi info nyata (bukan kata generik seperti "Produk Unggulan"):

```html
<section class="relative overflow-hidden bg-steel-900 text-white bg-[linear-gradient(to_right,rgba(255,255,255,.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,.04)_1px,transparent_1px)] bg-[size:32px_32px]">
    <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
        <p class="label-mono text-amber-600">CV ADZRA ENGINEERING — PADALARANG, BANDUNG BARAT</p>
        <h1 class="mt-4 max-w-2xl font-display text-4xl font-bold leading-tight sm:text-5xl">
            Fabrikasi Mesin Industri, Dibuat Sesuai Spesifikasi Anda
        </h1>
        <p class="mt-6 max-w-xl font-body text-steel-400">
            Rotary dryer, mesin cetak wood pellet, mesin cetak pelet — dirancang dan difabrikasi langsung oleh tim kami di Bandung.
        </p>
        <div class="mt-10 flex flex-wrap gap-4">
            <a href="{{ route('products.index') }}" class="rounded bg-amber-600 px-6 py-3 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700">
                Lihat Produk →
            </a>
            <a href="{{ $whatsappLink }}" class="rounded border border-white/25 px-6 py-3 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-white/5">
                Konsultasi WhatsApp
            </a>
        </div>
    </div>
</section>
```

Tombol memakai `font-mono uppercase tracking-widest` (bukan kalimat casual rounded-pill) — konsisten dengan gaya label mesin/panel kontrol.

### 4.2 Kartu Produk

Ganti kartu `rounded-xl shadow-sm hover:shadow-md` dengan `.plate` + label kode produk mono di atas nama produk:

```html
<a href="{{ route('products.show', $product->slug) }}" class="plate group block rounded p-0 transition hover:shadow-sm">
    <span class="plate-corner-bl"></span>
    <span class="plate-corner-br"></span>
    <div class="aspect-[4/3] overflow-hidden bg-paper-100">
        <img src="..." class="h-full w-full object-cover">
    </div>
    <div class="p-5">
        <p class="label-mono">{{ $product->category?->name }}</p>
        <h3 class="mt-1 font-display text-lg font-semibold text-graphite-900 group-hover:text-steel-700">
            {{ $product->name }}
        </h3>
        <div class="mt-3 flex items-center justify-between border-t border-line-200 pt-3">
            <span class="font-mono text-sm font-semibold text-steel-700">
                {{ $product->price ? 'Rp '.number_format($product->price,0,',','.') : 'Hubungi Kami' }}
            </span>
            <span class="label-mono text-amber-600">Detail →</span>
        </div>
    </div>
</a>
```

### 4.3 Tabel Spesifikasi (halaman detail produk)

Ini komponen paling penting untuk "menjual" tema nameplate — buat betul-betul terasa seperti membaca pelat data mesin:

```html
<div class="plate rounded p-6">
    <span class="plate-corner-bl"></span>
    <span class="plate-corner-br"></span>
    <p class="label-mono mb-4">Spesifikasi Teknis</p>
    <dl class="divide-y divide-line-200 font-mono text-sm">
        @foreach ($product->specifications as $spec)
            <div class="flex justify-between py-2">
                <dt class="text-graphite-500 uppercase">{{ $spec->spec_key }}</dt>
                <dd class="font-semibold text-graphite-900">{{ $spec->spec_value }}</dd>
            </div>
        @endforeach
    </dl>
</div>
```

### 4.4 List Keunggulan (ganti checklist bulat generik)

❌ Hindari lingkaran centang biru generik. ✅ Ganti jadi baris bernomor mono, seperti daftar item di gambar teknik:

```html
<ul class="mt-6 divide-y divide-line-200 border-y border-line-200">
    <li class="flex items-baseline gap-4 py-3">
        <span class="label-mono text-amber-600">01</span>
        <span class="font-body text-graphite-900">Fabrikasi mesin custom sesuai spesifikasi</span>
    </li>
    <li class="flex items-baseline gap-4 py-3">
        <span class="label-mono text-amber-600">02</span>
        <span class="font-body text-graphite-900">Material berkualitas dengan pengerjaan presisi</span>
    </li>
    <li class="flex items-baseline gap-4 py-3">
        <span class="label-mono text-amber-600">03</span>
        <span class="font-body text-graphite-900">Konsultasi dan layanan purna jual</span>
    </li>
</ul>
```

(Nomor di sini masuk akal dipakai karena memang daftar berurutan/terhitung, bukan sekadar hiasan.)

### 4.5 Navbar & Footer

- Background `steel-900`, teks putih/`steel-400`.
- Nav link pakai `font-mono text-xs uppercase tracking-widest`, bukan `font-body` biasa — supaya nav terasa seperti label panel kontrol.
- Logo + nama perusahaan rata kiri, menu rata kanan, tanpa dropdown bergaya rounded-shadow besar.

---

## 5. Do / Don't — Checklist Cepat

| ✅ Lakukan | ❌ Hindari |
|---|---|
| Radius kecil (`rounded`/`rounded-sm`) | `rounded-xl`/`rounded-2xl`/`rounded-full` di kartu & panel besar |
| Border 1px hairline sebagai pembatas | Shadow besar (`shadow-lg`/`shadow-xl`) sebagai andalan pemisah elemen |
| Label mono uppercase untuk eyebrow/kode | Badge pil warna-warni ("✨ Featured", "Produk Unggulan" dengan ikon bintang) |
| Amber hanya untuk 1–2 elemen penting per layar | Amber dipakai di banyak elemen sekaligus sampai terasa ramai |
| Frame siku (`.plate`) di kartu/panel utama | Ikon centang bulat generik untuk list keunggulan |
| Font mono untuk data/angka/spesifikasi | Semua teks pakai satu font body yang sama tanpa variasi peran |
| Grid garis tipis sebagai tekstur hero gelap | Gradasi warna penuh layar sebagai background hero |
| Transisi warna/border halus (150–200ms) | Animasi bounce/float/parallax berlebihan |

---

## 6. Ikonografi & Gambar

- Kalau perlu ikon, pakai set line-icon tipis (mis. Lucide/Heroicons outline) — jangan ikon 3D/gradient/emoji.
- Foto produk sebaiknya foto asli mesin dari CV Adzra (bukan stock photo orang kantoran generik). Kalau belum ada foto asli, mending pakai placeholder polos berlabel nama produk (seperti sudah ada fallback di kode) daripada stock photo yang tidak relevan.

---

## 7. Ringkasan Rasional

Semua pilihan di atas — pelat sudut siku, font mono untuk data, palet steel + amber yang desaturated, radius kecil — ditarik langsung dari benda nyata di dunia CV Adzra Engineering: gambar teknik, pelat nama mesin, dan warna cat safety di bengkel. Ini yang membedakan tema aplikasi ini dari template "korporat biru generik" yang bisa dipakai bisnis apa saja.
