<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\CashbookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WarrantyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Staff\AttendanceController as StaffAttendanceController;
use App\Http\Controllers\Staff\CashbookController as StaffCashbookController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\OrderController as StaffOrderController;
use App\Http\Controllers\Staff\StockController;
use App\Http\Controllers\Staff\TransactionController as StaffTransactionController;
use App\Http\Controllers\Staff\WorkerController as StaffWorkerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');
Route::view('/tentang', 'about')->name('about');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/kategori', CategoryController::class)->names('categories');
    Route::resource('/produk', AdminProductController::class)->names('products');
    Route::resource('/pelanggan', CustomerController::class)->names('customers');
    Route::resource('/pemesanan', OrderController::class)->names('orders');
    Route::get('/transaksi', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/garansi', [WarrantyController::class, 'index'])->name('warranty.index');
    Route::get('/kas', [CashbookController::class, 'index'])->name('cashbooks.index');

    Route::resource('/supplier', SupplierController::class)->names('suppliers');
    Route::resource('/akun', UserController::class)->names('users')->parameters(['akun' => 'user']);
    Route::get('/absensi', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/penggajian', [PayrollController::class, 'index'])->name('payrolls.index');
    Route::post('/penggajian/generate', [PayrollController::class, 'generate'])->name('payrolls.generate');
    Route::post('/penggajian/{payroll}/approve', [PayrollController::class, 'approve'])->name('payrolls.approve');
    Route::delete('/penggajian/{payroll}', [PayrollController::class, 'destroy'])->name('payrolls.destroy');
});

Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    Route::get('/transaksi', [StaffTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transaksi/create', [StaffTransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transaksi', [StaffTransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transaksi/{transaksi}/edit', [StaffTransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transaksi/{transaksi}', [StaffTransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transaksi/{transaksi}', [StaffTransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/transaksi/{transaksi}/invoice', [StaffTransactionController::class, 'invoice'])->name('transactions.invoice');

    Route::get('/kas/create', [StaffCashbookController::class, 'create'])->name('cashbooks.create');
    Route::post('/kas', [StaffCashbookController::class, 'store'])->name('cashbooks.store');

    Route::get('/pemesanan', [StaffOrderController::class, 'index'])->name('orders.index');
    Route::get('/pemesanan/{pemesanan}/edit', [StaffOrderController::class, 'edit'])->name('orders.edit');
    Route::put('/pemesanan/{pemesanan}', [StaffOrderController::class, 'update'])->name('orders.update');

    Route::get('/stok', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stok', [StockController::class, 'update'])->name('stock.update');

    Route::resource('/pekerja', StaffWorkerController::class)->names('workers');

    Route::get('/absensi', [StaffAttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/absensi', [StaffAttendanceController::class, 'store'])->name('attendances.store');
});
