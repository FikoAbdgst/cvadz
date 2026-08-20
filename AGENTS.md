# AGENTS.md

Sales website (Laravel 12) for CV Adzra Engineering Bandung (industrial machine fabrication). **MVP-complete** (public catalog + admin panel + staff panel). `PROJECT_CONTEXT.md` = schema & MVP spec; **`DESAIN.md` = visual authority** (supersedes PROJECT_CONTEXT §3 palette). Read both before structural changes.

## Hard constraints (do not violate)

- **Pure Laravel 12 / PHP 8.4.** Never install Breeze/Jetstream/Fortify or any starter kit — auth is manual (`AuthController` + `Auth::attempt`, routes `/login`, `/logout`).
- **No JS framework** (no Alpine/Vue/React/Livewire). Frontend interactivity is vanilla JS only. Tailwind CSS v4 via `@tailwindcss/vite`.
- **WhatsApp is just `https://wa.me/<number>?text=<urlencoded msg>` links.** Number comes from `config('services.whatsapp.number')` which falls back to `config('company.whatsapp')` (`6281322140457`), overridden by `WA_NUMBER` in `.env` (currently `6282263028951`). Build messages with `App\Support\WhatsApp::link()`, `Product::whatsappMessage()`, or `Service::whatsappMessage()`.
- **No public register page.** Admin account from `AdminSeeder` (`admin@cvadz.com` / `admin123`). Demo staff: `staff@cvadz.com` / `staff123` (role `staff`).
- Sales reports are **aggregation queries over `transactions`** — no report table.

## Architecture (things filenames won't tell you)

- **One combined admin "Transaksi" menu** at `GET /admin/transaksi` = `Admin\SalesController@index` with tabs `?tab=pelanggan|pemesanan|transaksi`. View: `resources/views/admin/sales/index.blade.php`.
  - The `transaksi` tab is **read-only monitoring** (asserted by `test_admin_transactions_tab_is_monitoring_only`). There is **no `admin.transactions.*` resource and no `Admin\TransactionController`** — payment input lives in the staff panel. Don't recreate admin transaction CRUD.
  - `admin.customers.index` / `admin.orders.index` still exist but just redirect to the sales tabs (keep old bookmarks working). Don't recreate standalone list views.
  - All customer/order CRUD redirects go to `route('admin.sales.index', ['tab' => ...])`.
- Admin resource routes are named via `->names('categories'|'products'|'customers'|'orders')` under group `admin.` (no transactions/transaksi resource). Public product routes use `products.*`.
- Public `ProductController` vs `Admin\ProductController` name clash: the admin one is imported `as AdminProductController`.
- **Models use traditional `$fillable`/`$hidden` property arrays.** Only `casts()` uses the modern method-based style (`protected function casts(): array`). Don't add `#[Fillable]` PHP attributes.
- **Status enums** live in `app/Enums` (`OrderStatus`, `TransactionStatus`, `PaymentStatus`): models cast to them (`'status' => OrderStatus::class`) so views use `$model->status->value` / `->label()`; controllers pass `::cases()` to views for filter chips. `PaymentStatus` (`belum|dp|lunas`) lives on `orders.payment_status` and controls whether orders appear in the staff workflow.
- Brand logo = `public/logo.png` (transparent PNG, used in navbar, footer, admin sidebar, login).
- **Operational entities:** `services`, `suppliers`, `workers`, `attendances`, `cashbooks` (+ `warranty_months` on products, `admin_user_id`/`total`/`warranty_end_date` on orders, `staff_user_id`/`payment_type` on transactions, `transaction_id` FK on cashbooks). Public route `GET /layanan` = `ServiceController@index`, view `resources/views/services/index.blade.php`. `services` has no CRUD yet (planned); workers/attendances/cashbook input live in the staff panel. Spareparts are a product category (`sparepart`), not a separate table.
- **Part 2 (admin Dashboard — Master Data & HR/Keuangan):**
  - `categories.type` ('produk'|'layanan') drives category tabs; public product/category filters always restrict `type='produk'`; `services.category_id` links to a layanan category.
  - `workers.salary` = upah harian (daily wage).
  - Admin sidebar groups: **Master Data** (Kategori, Produk, Supplier) · **Penjualan** (Pemesanan, Laporan, Cek Garansi) · **HR & Keuangan** (Buku Kas, Kelola Akun = `admin.users.*`, Rekap Absensi = `admin.attendances.index` read-only, Penggajian = `admin.payrolls.*`).
  - **Payroll flow (weekly):** POST `admin.payrolls.generate` (period `Y-m-d`, Monday-based week) computes `total_days` from `attendances` (Mon–Sat) × salary → draft rows. Drafts allow editing bonus, lemburan (overtime), uang_luar_kota (out-of-town allowance), and kasbon (cash advance); `Payroll::netSalary()` = `salary_amount + bonus + lemburan + uang_luar_kota - kasbon`. POST `admin.payrolls.approve` flips status to approved (approved_by/at) **and inserts a `cashbooks` pengeluaran row** (user_id = approver); DELETE only works on drafts. Pay slips at `admin.payrolls.slip` (blade) and `admin.payrolls.slip-pdf` (DomPDF).
  - **Route gotcha:** `admin.users.*` uses `->parameters(['akun' => 'user'])` — so `UserRequest` `$this->route('user')` and `User $user` binding work (param is `{user}`, not `{akun}`).
- **Part 3 (sales & finance):**
  - An order's item is a **product OR a service** — `orders.product_id` is nullable and `orders.service_id` (nullable FK) was added. Always render via `Order::itemLabel()` (`product?->name ?? service?->name`); eager load `['customer','product','service']` everywhere it's shown (sales tabs, dashboard recent orders, transaction order select).
  - **Payment is recorded by admin at order creation/edit** — `orders` has `payment_status` (belum|dp|lunas), `payment_amount`, `payment_type` (tunai|transfer|lainnya), `payment_date`, `payment_proof` (image stored in `storage/app/public/payments/`). Admin order form: pick customer + item + total (Rp, optional) + `warranty_end_date` + payment section (status, amount, type, date, proof upload). `OrderRequest` validates `payment_status` required; if dp/lunas, `payment_amount` must be ≥ 1.
  - Admin `OrderController::syncPayment()` creates/updates a single `Transaction` + `Cashbook` pemasukan row from order payment fields.
  - **Order production status** (`OrderStatus` pending/diproses/selesai/batal) is staff's domain — admin does NOT set it.
  - `products.stock` (unsigned int, default 0) + `Product::LOW_STOCK_THRESHOLD` (=5) + `isLowStock()`/`stockStatus()` ('aman'|'kritis'|'habis'); ProductController builds the create/update array manually, so `stock` was added explicitly there too.
  - Warranty status from `Order::warrantyStatus()` ('aktif'|'kedaluwarsa'|'tanpa_garansi'), page = `admin.warranty.index` (`?q=` matches customer name LIKE or order id).
  - Buku Kas = `admin.cashbooks.index` (filter `type`+`from`/`to`; balance = sum(pemasukan) − sum(pengeluaran) since `cashbooks.amount` is always positive).
  - Reports = single `admin.reports.index` with tabs `?tab=penjualan|stok|kas|penggajian` (date filter only on penjualan/kas tabs; `stok` uses stockStatus, `penggajian` groups payrolls by `period` Y-m). Sidebar: "Laporan" + "Cek Garansi" under Penjualan, "Buku Kas" at top of HR & Keuangan.
- **Part 4 (Staff Operasional panel, role `staff`):**
  - Role middleware `App\Http\Middleware\EnsureUserHasRole` alias `role` (in `bootstrap/app.php`); admin group is `middleware(['auth','role:admin'])`, staff group `['auth','role:staff']` prefix `/staff` name `staff.`. Wrong-role access → redirect to own dashboard (`staff.dashboard` for staff, else `admin.dashboard`) with flash `error`. `AuthController@login` redirects `intended()` to the dashboard matching role. Layout `layouts/admin.blade.php` splits sidebar by `auth()->user()->role` (`@if/@else`); sidebar/topbar/overlay carry `print:hidden` so the staff invoice prints clean.
  - **Payment workflow:** admin records payment (status DP/Lunas + proof photo) at order creation/edit. Staff sees only **paid orders** (payment_status dp/lunas) in "Perlu Dikerjakan" tab. Staff can **verify/ACC** dp→lunas via POST `staff.transactions.verify` (after checking bank). Staff can **edit** transactions (amount/type/date/status — changes sync back to order payment fields + cashbook via `syncOrderFromTransaction`/`syncPaymentCashbook`) and **delete** transactions (deletes linked cashbook first, resets order payment_status to belum). Staff can still print invoice (`staff.transactions.invoice`, kwitansi `#TRX-{id}`, `window.print()`).
  - Staff "Daftar Transaksi" (`staff.transactions.index`) has tabs `?tab=pesanan|transaksi` (default `pesanan`): the `pesanan` tab lists orders that are **paid but not yet transacted by staff** (with proof photo preview, ACC button, Progress/Faktur links); the `transaksi` tab is the transaction history + filters. **"Progress Pemesanan"** is a separate sidebar menu under "Operasional" for editing order production status + notes (`staff.orders.edit/update`).
  - Expense = `staff.cashbooks.create/store` (type forced `pengeluaran`). Progress = `staff.orders.edit/update` (status+notes; proof photo displayed read-only). Stock = `staff.stock.update` (tambah/kurang; decrement refused if stock < qty, error key `quantity`). Workers = `staff.workers.*` resource param `{pekerja}` (`Worker $pekerja` binding; index view variable is `$pekerja`); salary = upah harian. Attendance = `staff.attendances.index?date=` + `store` (duplicate worker+date → error `worker_id`; `check_out < check_in` → error `check_out`).
  - Routes to remember: `staff.transactions.verify` (POST, ACC dp→lunas), `staff.stock.update` is POST to `/staff/stok` (not resource). Staff requests: `StaffTransactionRequest`, `StaffCashbookRequest`, `StaffOrderRequest`, `StockRequest`, `WorkerRequest`, `AttendanceRequest`. Tests: `tests/Feature/StaffPanelTest.php` (18 tests: role access control, invoice, expense, order progress, stock, worker CRUD, attendance + duplicates, transaction edit/delete + cashbook sync, verify ACC, paid orders list, unpaid order hidden).

## Design system

- **`DESAIN.md` is the visual authority** (theme: "Spec Sheet / Nameplate Industrial"). `PROJECT_CONTEXT.md` §3 still documents the old generic palette (blue `#1D4ED8`, Poppins/Inter) — **superseded**, don't use it for new UI.
- Tokens live in `@theme { ... }` in `resources/css/app.css` (`--color-steel-*`, `--color-paper-100`, `--color-graphite-*`, `--color-line-200`, `--color-amber-600/700`), used as `bg-steel-900`, `text-graphite-900`, `border-line-200`, etc.
- Palette (not Tailwind defaults): steel-900 `#0F2A42` (dark navbar/hero), steel-700 `#1C4C78` (primary actions), steel-400 `#5C86AC` (secondary), paper-100 `#F2F5F7` (page bg), graphite-900 `#1B222B` (ink), graphite-500 `#5C6773` (muted), line-200 `#D7E0E6` (border), amber-600 `#D98A2B` (accent).
- Fonts: display = Space Grotesk (`font-display`), body = IBM Plex Sans (`font-body`), data/labels = IBM Plex Mono (`font-mono`); imported via Google Fonts in `layouts/app.blade.php` and `layouts/admin.blade.php`.
- **Amber is scarce on purpose** — max 1–2 elements per screen (primary CTA + corner-bracket accents), never broad.
- Signature components: `.plate` = panel with 4 amber corner brackets (needs `<span class="plate-corner-bl">` / `plate-corner-br` inside), `label-mono` = uppercase mono eyebrow. Small radii (`rounded`/`rounded-sm`), thin `line-200` hairline borders over shadows. Avoid `rounded-xl/2xl`, pill badges, gradient heroes, and emoji icons.

## Testing gotchas (high-value)

- **`php artisan test` output is intercepted and truncated to ~2KB JSON.** Always redirect to a log and read it:
  `php artisan test > /c/Users/impax/AppData/Local/Temp/opencode/test.log 2>&1`
- Tests run on **SQLite `:memory:`** (`phpunit.xml`), but dev uses MySQL. Don't rely on MySQL-specific behavior (see date gotcha below).
- **`date` cast stores `'Y-m-d H:i:s'` on SQLite** (MySQL DATE truncates the time). So string `whereBetween('transaction_date', [from, to])` misses the boundary date on SQLite. Use the `whereDate('col','>=',$from)->whereDate('col','<=',$to)` pattern (see `ReportController`/`DashboardController`).
- **Misleading error:** a failed `assertRedirect` (e.g. validation redirects back because a required field is missing) crashes as `Call to a member function all() on array` in `TestResponseAssert::injectResponseContext`. Root cause is usually a Form Request failing validation, not the redirect itself. Check the request payload against the Form Request rules.
- **Form Requests: `slug` must be `nullable`** (controllers auto-generate via `Str::slug`) — marking it `required` silently breaks store/update tests with the error above.
- `test_admin_can_create_product_with_images_specs_and_videos` leaves `.jpg` artifacts in `storage/app/public/products/` — clean them up after running the suite (`rm storage/app/public/products/*.jpg`).
- Total test count: 51 tests across 6 Feature test files (`AdminAuthTest`, `AdminCrudTest`, `AdminOperationsTest`, `AdminSalesReportTest`, `PublicCatalogTest`, `StaffPanelTest`).

## Dev flow & commands

- Env: Windows + Git Bash, PHP 8.4 (C:\laragon), MySQL 8.4 at 127.0.0.1:3306, DB `cvadz`, root/empty password. `.env`: `APP_LOCALE=id`, `DB_CONNECTION=mysql`.
- Verify after changes (in order): `vendor/bin/pint` (PSR-12) → `php artisan test` (log it) → `npm run build` → `php artisan serve --port=8000`.
- **Dev workflow (1 terminal, no build/restart):** `npm run dev` = `concurrently "php artisan serve" "vite"` — this is the ONLY command the user needs while developing. It runs PHP server (:8000) + Vite dev server (:5173) together and creates `public/hot` (so `@vite()` serves hot assets instead of `public/build`). With `refresh: true` in `vite.config.js`, editing Blade/PHP/CSS/JS auto-reloads the browser — no manual refresh, no `npm run build`, no serve restart. `php artisan serve` (Laravel 12) also auto-restarts itself on `.env` changes (unless `--no-reload`).
  - Never run `php artisan serve` while `npm run dev` is active — port 8000 conflicts ("Address already in use").
  - `public/hot` must NOT be committed/kept; it is created by Vite dev server and removed when it stops. For a production/preview run: `npm run build`, then `php artisan serve` alone (no vite, no hot file).
- Run one test: `php artisan test --filter=NameOfTest` (output also intercepted — log it too).
- `php artisan serve` + curl **multipart uploads fail on Windows** (curl 000, no response). Use the feature test for upload flows; verify other pages with plain GET curl.
- `composer` installs of `laravel/pint` have been flaky on this machine (codeload "Permission denied" / git-SSH hang). Workaround: `composer clear-cache` then retry.
- `.npmrc` has `ignore-scripts=true` — `npm install` skips postinstall hooks. This is intentional; don't add `--ignore-scripts` flags.
- `AppServiceProvider` forces HTTPS in local env (`URL::forceScheme('https')`) — this can interfere with plain HTTP curl tests; use `127.0.0.1` (not `localhost`) to avoid redirect issues.
- Seeded demo data: `DemoSeeder` (categories, products w/ SVG placeholders, customers, orders, transactions). `storage:link` already created.
