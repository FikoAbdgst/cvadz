<?php

namespace Tests\Feature;

use App\Enums\TransactionStatus;
use App\Models\Cashbook;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payroll;
use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSalesReportTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_order_with_service_total_and_warranty(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        $customer = Customer::factory()->create();
        $service = Service::create(['name' => 'Custom Mesin', 'slug' => 'custom-mesin', 'description' => 'Fabrikasi custom']);

        $this->post(route('admin.orders.store'), [
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'quantity' => 1,
            'total' => 15000000,
            'warranty_end_date' => now()->addMonth()->toDateString(),
            'status' => 'diproses',
        ])->assertRedirect(route('admin.sales.index', ['tab' => 'pemesanan']));

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'total' => 15000000,
            'admin_user_id' => $admin->id,
        ]);
    }

    public function test_order_requires_product_or_service(): void
    {
        $this->actingAs($this->admin());
        $customer = Customer::factory()->create();

        $this->from(route('admin.orders.create'))
            ->post(route('admin.orders.store'), [
                'customer_id' => $customer->id,
                'quantity' => 1,
                'status' => 'pending',
            ])
            ->assertSessionHasErrors('item');
    }

    public function test_admin_can_create_product_with_stock(): void
    {
        $this->actingAs($this->admin());
        $category = Category::factory()->create();

        $this->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Mesin Test Stok',
            'description' => 'Deskripsi',
            'price' => 1000000,
            'stock' => 3,
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::where('slug', 'mesin-test-stok')->firstOrFail();
        $this->assertEquals(3, $product->stock);
        $this->assertTrue($product->isLowStock());
    }

    public function test_warranty_page_shows_active_and_expired_status(): void
    {
        $this->actingAs($this->admin());

        $active = Order::factory()->create([
            'warranty_end_date' => now()->addMonth()->toDateString(),
        ]);
        $expired = Order::factory()->create([
            'warranty_end_date' => now()->subMonth()->toDateString(),
        ]);
        Order::factory()->create(['warranty_end_date' => null]);

        $this->get(route('admin.warranty.index'))
            ->assertOk()
            ->assertSee($active->customer->name)
            ->assertSee($expired->customer->name)
            ->assertSee('Aktif')
            ->assertSee('Kedaluwarsa');
    }

    public function test_warranty_search_by_customer_name_and_order_number(): void
    {
        $this->actingAs($this->admin());

        $order = Order::factory()->create([
            'warranty_end_date' => now()->addMonth()->toDateString(),
        ]);
        Order::factory()->create([
            'warranty_end_date' => now()->addMonth()->toDateString(),
        ]);

        $this->get(route('admin.warranty.index', ['q' => $order->customer->name]))
            ->assertOk()
            ->assertSee($order->customer->name);

        $this->get(route('admin.warranty.index', ['q' => $order->id]))
            ->assertOk()
            ->assertSee($order->customer->name);
    }

    public function test_cashbook_page_shows_entries_and_balance(): void
    {
        $this->actingAs($this->admin());

        Cashbook::create([
            'type' => 'pemasukan',
            'amount' => 15000000,
            'description' => 'DP pemesanan Rotary Dryer',
            'transaction_date' => now()->toDateString(),
            'user_id' => $this->admin()->id,
        ]);

        Cashbook::create([
            'type' => 'pengeluaran',
            'amount' => 5000000,
            'description' => 'Pembelian plat baja',
            'transaction_date' => now()->toDateString(),
            'user_id' => $this->admin()->id,
        ]);

        $this->get(route('admin.cashbooks.index'))
            ->assertOk()
            ->assertSee('DP pemesanan Rotary Dryer')
            ->assertSee('Pembelian plat baja')
            ->assertSee('15.000.000')
            ->assertSee('5.000.000')
            ->assertSee('10.000.000');
    }

    public function test_dashboard_shows_total_sales_critical_stock_and_cash_balance(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        Product::factory()->create(['stock' => 2]);
        Product::factory()->create(['stock' => 10]);

        Cashbook::create([
            'type' => 'pemasukan',
            'amount' => 10000000,
            'description' => 'Pemasukan',
            'transaction_date' => now()->toDateString(),
        ]);

        $order = Order::factory()->create();
        Transaction::create([
            'order_id' => $order->id,
            'amount' => 5000000,
            'transaction_date' => now()->toDateString(),
            'status' => TransactionStatus::Lunas,
        ]);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('5.000.000')
            ->assertSee('10.000.000');
    }

    public function test_reports_tabs_render(): void
    {
        $this->actingAs($this->admin());

        $product = Product::factory()->create(['stock' => 1]);

        $worker = Worker::create(['name' => 'Dedi Kurniawan', 'position' => 'Kepala Bengkel', 'phone' => '0812', 'salary' => 150000]);
        Payroll::create([
            'worker_id' => $worker->id,
            'period' => now()->format('Y-m'),
            'total_days' => 3,
            'salary_amount' => 450000,
            'status' => 'draft',
        ]);

        Cashbook::create([
            'type' => 'pemasukan',
            'amount' => 20000000,
            'description' => 'Pemasukan test',
            'transaction_date' => now()->toDateString(),
        ]);

        $this->get(route('admin.reports.index', ['tab' => 'penjualan']))->assertOk();
        $this->get(route('admin.reports.index', ['tab' => 'stok']))
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee('Kritis');
        $this->get(route('admin.reports.index', ['tab' => 'kas']))
            ->assertOk()
            ->assertSee('Pemasukan test')
            ->assertSee('20.000.000');
        $this->get(route('admin.reports.index', ['tab' => 'penggajian']))
            ->assertOk()
            ->assertSee('Dedi Kurniawan')
            ->assertSee('450.000');
    }
}
