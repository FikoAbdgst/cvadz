<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Cashbook;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffPanelTest extends TestCase
{
    use RefreshDatabase;

    private function staff(): User
    {
        return User::factory()->create(['role' => 'staff']);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_staff_login_redirects_to_staff_dashboard(): void
    {
        User::factory()->create(['role' => 'staff', 'email' => 'staff@test.com', 'password' => bcrypt('secret123')]);

        $this->post(route('login.attempt'), ['email' => 'staff@test.com', 'password' => 'secret123'])
            ->assertRedirect(route('staff.dashboard'));
    }

    public function test_staff_can_view_dashboard(): void
    {
        $this->actingAs($this->staff());

        $order = Order::factory()->create(['status' => 'pending']);

        $this->get(route('staff.dashboard'))
            ->assertOk()
            ->assertSee($order->customer->name)
            ->assertSee('Dashboard Operasional');
    }

    public function test_staff_cannot_access_admin_pages(): void
    {
        $this->actingAs($this->staff());

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('staff.dashboard'))
            ->assertSessionHas('error');
    }

    public function test_admin_cannot_access_staff_pages(): void
    {
        $this->actingAs($this->admin());

        $this->get(route('staff.dashboard'))
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('error');
    }

    public function test_staff_full_payment_completes_order_and_records_cashbook(): void
    {
        $staff = $this->staff();
        $this->actingAs($staff);

        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->post(route('staff.transactions.store'), [
            'order_id' => $order->id,
            'amount' => 15000000,
            'payment_type' => 'transfer',
            'transaction_date' => now()->toDateString(),
            'status' => 'lunas',
        ]);

        $transaction = Transaction::where('order_id', $order->id)->firstOrFail();

        $response->assertRedirect(route('staff.transactions.invoice', $transaction));

        $this->assertEquals('lunas', $transaction->status->value);
        $this->assertEquals($staff->id, $transaction->staff_user_id);
        $this->assertEquals('selesai', $order->fresh()->status->value);

        $this->assertDatabaseHas('cashbooks', [
            'type' => 'pemasukan',
            'amount' => 15000000,
            'user_id' => $staff->id,
        ]);
    }

    public function test_staff_partial_payment_keeps_order_status(): void
    {
        $this->actingAs($this->staff());

        $order = Order::factory()->create(['status' => 'pending']);

        $this->post(route('staff.transactions.store'), [
            'order_id' => $order->id,
            'amount' => 5000000,
            'payment_type' => 'tunai',
            'transaction_date' => now()->toDateString(),
            'status' => 'belum_lunas',
        ])->assertRedirect();

        $this->assertEquals('pending', $order->fresh()->status->value);
        $this->assertDatabaseHas('cashbooks', [
            'type' => 'pemasukan',
            'amount' => 5000000,
        ]);
    }

    public function test_staff_can_view_invoice(): void
    {
        $this->actingAs($this->staff());

        $order = Order::factory()->create();
        $transaction = Transaction::create([
            'order_id' => $order->id,
            'staff_user_id' => $this->staff()->id,
            'amount' => 7500000,
            'payment_type' => 'transfer',
            'transaction_date' => now()->toDateString(),
            'status' => 'lunas',
        ]);

        $this->get(route('staff.transactions.invoice', $transaction))
            ->assertOk()
            ->assertSee('#TRX-'.$transaction->id)
            ->assertSee($order->customer->name)
            ->assertSee('7.500.000');
    }

    public function test_staff_can_record_operational_expense(): void
    {
        $staff = $this->staff();
        $this->actingAs($staff);

        $this->post(route('staff.cashbooks.store'), [
            'description' => 'Bensin mesin las',
            'amount' => 150000,
            'transaction_date' => now()->toDateString(),
        ])->assertRedirect(route('staff.cashbooks.create'));

        $this->assertDatabaseHas('cashbooks', [
            'type' => 'pengeluaran',
            'description' => 'Bensin mesin las',
            'amount' => 150000,
            'user_id' => $staff->id,
        ]);
    }

    public function test_staff_can_update_order_progress(): void
    {
        $this->actingAs($this->staff());

        $order = Order::factory()->create(['status' => 'pending']);

        $this->put(route('staff.orders.update', $order), [
            'status' => 'diproses',
            'notes' => 'Sudah masuk jadwal produksi',
        ])->assertRedirect(route('staff.orders.edit', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'diproses',
            'notes' => 'Sudah masuk jadwal produksi',
        ]);
    }

    public function test_staff_can_adjust_stock_up_and_down(): void
    {
        $this->actingAs($this->staff());

        $product = Product::factory()->create(['stock' => 5]);

        $this->post(route('staff.stock.update'), [
            'product_id' => $product->id,
            'action' => 'tambah',
            'quantity' => 3,
        ])->assertRedirect(route('staff.stock.index'));

        $this->assertEquals(8, $product->fresh()->stock);

        $this->post(route('staff.stock.update'), [
            'product_id' => $product->id,
            'action' => 'kurang',
            'quantity' => 2,
        ])->assertRedirect(route('staff.stock.index'));

        $this->assertEquals(6, $product->fresh()->stock);
    }

    public function test_stock_cannot_go_below_zero(): void
    {
        $this->actingAs($this->staff());

        $product = Product::factory()->create(['stock' => 3]);

        $this->from(route('staff.stock.index'))
            ->post(route('staff.stock.update'), [
                'product_id' => $product->id,
                'action' => 'kurang',
                'quantity' => 10,
            ])
            ->assertSessionHasErrors('quantity');

        $this->assertEquals(3, $product->fresh()->stock);
    }

    public function test_staff_can_crud_worker(): void
    {
        $this->actingAs($this->staff());

        $this->post(route('staff.workers.store'), [
            'name' => 'Budi Santoso',
            'position' => 'Tukang Las',
            'phone' => '0812-3456-7890',
            'salary' => 175000,
        ])->assertRedirect(route('staff.workers.index'));

        $worker = Worker::where('name', 'Budi Santoso')->firstOrFail();

        $this->put(route('staff.workers.update', $worker), [
            'name' => 'Budi Santoso',
            'position' => 'Tukang Las Senior',
            'phone' => '0812-3456-7890',
            'salary' => 200000,
        ])->assertRedirect(route('staff.workers.index'));

        $this->assertDatabaseHas('workers', ['id' => $worker->id, 'salary' => 200000]);

        $this->delete(route('staff.workers.destroy', $worker))
            ->assertRedirect(route('staff.workers.index'));

        $this->assertDatabaseMissing('workers', ['id' => $worker->id]);
    }

    public function test_staff_can_record_attendance(): void
    {
        $this->actingAs($this->staff());

        $worker = Worker::create(['name' => 'Dedi Kurniawan', 'position' => 'Kepala Bengkel', 'salary' => 150000]);

        $this->post(route('staff.attendances.store'), [
            'worker_id' => $worker->id,
            'date' => now()->toDateString(),
            'check_in' => '08:00',
            'check_out' => '16:30',
        ])->assertRedirect(route('staff.attendances.index', ['date' => now()->toDateString()]));

        $this->assertDatabaseHas('attendances', [
            'worker_id' => $worker->id,
            'check_in' => '08:00',
        ]);

        $this->get(route('staff.attendances.index'))
            ->assertOk()
            ->assertSee('Dedi Kurniawan');
    }

    public function test_duplicate_attendance_for_same_day_is_rejected(): void
    {
        $this->actingAs($this->staff());

        $worker = Worker::create(['name' => 'Dedi Kurniawan', 'position' => 'Kepala Bengkel', 'salary' => 150000]);
        $today = now()->toDateString();

        Attendance::create(['worker_id' => $worker->id, 'date' => $today, 'check_in' => '08:00:00']);

        $this->from(route('staff.attendances.index'))
            ->post(route('staff.attendances.store'), [
                'worker_id' => $worker->id,
                'date' => $today,
                'check_in' => '08:30',
                'check_out' => '16:00',
            ])
            ->assertSessionHasErrors('worker_id');

        $this->assertEquals(1, Attendance::where('worker_id', $worker->id)->whereDate('date', $today)->count());
    }

    public function test_check_out_cannot_be_before_check_in(): void
    {
        $this->actingAs($this->staff());

        $worker = Worker::create(['name' => 'Dedi Kurniawan', 'position' => 'Kepala Bengkel', 'salary' => 150000]);

        $this->from(route('staff.attendances.index'))
            ->post(route('staff.attendances.store'), [
                'worker_id' => $worker->id,
                'date' => now()->toDateString(),
                'check_in' => '14:00',
                'check_out' => '08:00',
            ])
            ->assertSessionHasErrors('check_out');
    }

    public function test_staff_transaction_requires_order(): void
    {
        $this->actingAs($this->staff());

        $this->from(route('staff.transactions.create'))
            ->post(route('staff.transactions.store'), [
                'amount' => 100000,
                'payment_type' => 'tunai',
                'transaction_date' => now()->toDateString(),
                'status' => 'lunas',
            ])
            ->assertSessionHasErrors('order_id');
    }

    public function test_staff_can_edit_transaction_and_cashbook_syncs(): void
    {
        $this->actingAs($this->staff());

        $order = Order::factory()->create(['status' => 'pending']);

        $this->post(route('staff.transactions.store'), [
            'order_id' => $order->id,
            'amount' => 5000000,
            'payment_type' => 'tunai',
            'transaction_date' => now()->toDateString(),
            'status' => 'belum_lunas',
        ]);

        $transaction = Transaction::where('order_id', $order->id)->firstOrFail();
        $this->assertDatabaseHas('cashbooks', ['type' => 'pemasukan', 'transaction_id' => $transaction->id, 'amount' => 5000000]);

        $this->put(route('staff.transactions.update', $transaction), [
            'order_id' => $order->id,
            'amount' => 8000000,
            'payment_type' => 'transfer',
            'transaction_date' => now()->toDateString(),
            'status' => 'belum_lunas',
        ])->assertRedirect(route('staff.transactions.index'));

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'amount' => 8000000]);
        $this->assertDatabaseHas('cashbooks', [
            'transaction_id' => $transaction->id,
            'type' => 'pemasukan',
            'amount' => 8000000,
        ]);
    }

    public function test_staff_edit_to_lunas_completes_order(): void
    {
        $this->actingAs($this->staff());

        $order = Order::factory()->create(['status' => 'pending']);
        $transaction = Transaction::create([
            'order_id' => $order->id,
            'staff_user_id' => $this->staff()->id,
            'amount' => 5000000,
            'payment_type' => 'tunai',
            'transaction_date' => now()->toDateString(),
            'status' => 'belum_lunas',
        ]);

        $this->put(route('staff.transactions.update', $transaction), [
            'order_id' => $order->id,
            'amount' => 15000000,
            'payment_type' => 'tunai',
            'transaction_date' => now()->toDateString(),
            'status' => 'lunas',
        ])->assertRedirect(route('staff.transactions.index'));

        $this->assertEquals('selesai', $order->fresh()->status->value);
    }

    public function test_staff_can_delete_transaction_and_linked_cashbook(): void
    {
        $staff = $this->staff();
        $this->actingAs($staff);

        $order = Order::factory()->create();
        $transaction = Transaction::create([
            'order_id' => $order->id,
            'staff_user_id' => $staff->id,
            'amount' => 10000000,
            'payment_type' => 'transfer',
            'transaction_date' => now()->toDateString(),
            'status' => 'lunas',
        ]);

        $cashbook = Cashbook::create([
            'type' => 'pemasukan',
            'amount' => $transaction->amount,
            'description' => 'Pembayaran #'.$order->id.' — '.$order->customer->name.' (Lunas)',
            'transaction_date' => $transaction->transaction_date,
            'user_id' => $staff->id,
            'transaction_id' => $transaction->id,
        ]);

        $this->delete(route('staff.transactions.destroy', $transaction))
            ->assertRedirect(route('staff.transactions.index'));

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
        $this->assertDatabaseMissing('cashbooks', ['id' => $cashbook->id]);
    }
}
