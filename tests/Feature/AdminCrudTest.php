<?php

namespace Tests\Feature;

use App\Enums\TransactionStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_guest_is_redirected_from_admin_pages(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.products.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_access_all_admin_pages(): void
    {
        $this->actingAs($this->admin());

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.categories.index'))->assertOk();
        $this->get(route('admin.categories.create'))->assertOk();
        $this->get(route('admin.products.index'))->assertOk();
        $this->get(route('admin.products.create'))->assertOk();
        $this->get(route('admin.sales.index'))->assertOk();
        $this->get(route('admin.sales.index', ['tab' => 'pemesanan']))->assertOk();
        $this->get(route('admin.sales.index', ['tab' => 'transaksi']))->assertOk();
        $this->get(route('admin.customers.create'))->assertOk();
        $this->get(route('admin.orders.create'))->assertOk();
        $this->get(route('admin.reports.index'))->assertOk();
        $this->get(route('admin.suppliers.index'))->assertOk();
        $this->get(route('admin.suppliers.create'))->assertOk();
        $this->get(route('admin.users.index'))->assertOk();
        $this->get(route('admin.users.create'))->assertOk();
        $this->get(route('admin.attendances.index'))->assertOk();
        $this->get(route('admin.payrolls.index'))->assertOk();
        $this->get(route('admin.warranty.index'))->assertOk();
        $this->get(route('admin.cashbooks.index'))->assertOk();
        $this->get(route('admin.reports.index', ['tab' => 'stok']))->assertOk();
        $this->get(route('admin.reports.index', ['tab' => 'kas']))->assertOk();
        $this->get(route('admin.reports.index', ['tab' => 'penggajian']))->assertOk();
    }

    public function test_admin_can_create_category(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.categories.store'), [
            'name' => 'Rotary Dryer',
            'type' => 'produk',
        ])->assertRedirect(route('admin.categories.index', ['type' => 'produk']));

        $this->assertDatabaseHas('categories', ['slug' => 'rotary-dryer', 'type' => 'produk']);
    }

    public function test_admin_can_create_product_with_images_specs_and_videos(): void
    {
        $this->actingAs($this->admin());
        $category = Category::factory()->create();

        $response = $this->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Mesin Rotary Dryer 1 Ton',
            'description' => 'Deskripsi produk',
            'price' => 185000000,
            'is_featured' => 1,
            'images' => [UploadedFile::fake()->image('gambar.jpg')],
            'specifications' => [
                ['spec_key' => 'Kapasitas', 'spec_value' => '1 ton/jam'],
            ],
            'videos' => [
                ['video_url' => 'https://www.youtube.com/watch?v=abc123', 'caption' => 'Video demo'],
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('slug', 'mesin-rotary-dryer-1-ton')->first();

        $this->assertNotNull($product);
        $this->assertTrue($product->is_featured);
        $this->assertDatabaseHas('product_specifications', ['product_id' => $product->id, 'spec_key' => 'Kapasitas']);
        $this->assertDatabaseHas('product_videos', ['product_id' => $product->id]);
        $this->assertDatabaseHas('product_images', ['product_id' => $product->id, 'is_primary' => true]);
    }

    public function test_admin_can_update_order_payment(): void
    {
        $this->actingAs($this->admin());
        $order = Order::factory()->create(['payment_status' => 'belum']);

        $this->put(route('admin.orders.update', $order), [
            'customer_id' => $order->customer_id,
            'product_id' => $order->product_id,
            'quantity' => $order->quantity,
            'payment_status' => 'lunas',
            'payment_amount' => 15000000,
            'payment_type' => 'transfer',
            'payment_date' => now()->toDateString(),
        ])->assertRedirect(route('admin.sales.index', ['tab' => 'pemesanan']));

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'payment_status' => 'lunas']);
        $this->assertDatabaseHas('transactions', ['order_id' => $order->id, 'amount' => 15000000]);
        $this->assertDatabaseHas('cashbooks', ['type' => 'pemasukan']);
    }

    public function test_admin_transactions_tab_is_monitoring_only(): void
    {
        $this->actingAs($this->admin());
        $order = Order::factory()->create();
        Transaction::factory()->create([
            'order_id' => $order->id,
            'amount' => 5000000,
            'transaction_date' => now()->toDateString(),
            'status' => TransactionStatus::Lunas,
        ]);

        $this->get(route('admin.sales.index', ['tab' => 'transaksi']))
            ->assertOk()
            ->assertSee($order->customer->name)
            ->assertDontSee('Buat Transaksi');
    }

    public function test_report_shows_totals(): void
    {
        $this->actingAs($this->admin());
        $order = Order::factory()->create();
        Transaction::factory()->create([
            'order_id' => $order->id,
            'amount' => 5000000,
            'transaction_date' => now()->toDateString(),
            'status' => TransactionStatus::Lunas,
        ]);

        $this->get(route('admin.reports.index'))
            ->assertOk()
            ->assertSee('5.000.000');
    }
}
