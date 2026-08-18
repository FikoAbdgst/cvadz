<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Cashbook;
use App\Models\Category;
use App\Models\Payroll;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOperationsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_and_update_supplier(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.suppliers.store'), [
            'name' => 'PT Baja Utama',
            'contact_name' => 'Bpk. Hendra',
            'phone' => '021-5551001',
            'email' => 'sales@bajautama.co.id',
            'address' => 'Jl. Raya Bekasi Km 22',
        ])->assertRedirect(route('admin.suppliers.index'));

        $supplier = Supplier::where('email', 'sales@bajautama.co.id')->firstOrFail();

        $this->put(route('admin.suppliers.update', $supplier), [
            'name' => 'PT Baja Utama Steel',
            'contact_name' => 'Bpk. Hendra',
            'phone' => '021-5551001',
            'email' => 'sales@bajautama.co.id',
            'address' => 'Jl. Raya Bekasi Km 22',
        ])->assertRedirect(route('admin.suppliers.index'));

        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id, 'name' => 'PT Baja Utama Steel']);
    }

    public function test_admin_can_create_and_update_user_account(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.users.store'), [
            'name' => 'Sari Dewi',
            'email' => 'sari@cvadz.com',
            'password' => 'rahasia123',
            'role' => 'staff',
        ])->assertRedirect(route('admin.users.index'));

        $user = User::where('email', 'sari@cvadz.com')->firstOrFail();
        $this->assertEquals('staff', $user->role);

        $this->put(route('admin.users.update', $user), [
            'name' => 'Sari Dewi',
            'email' => 'sari@cvadz.com',
            'password' => 'baru456',
            'role' => 'staff',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertTrue(password_verify('baru456', $user->fresh()->password));
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        $this->from(route('admin.users.index'))
            ->delete(route('admin.users.destroy', $admin))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_view_attendance_recap(): void
    {
        $this->actingAs($this->admin());
        $worker = Worker::create(['name' => 'Dedi Kurniawan', 'position' => 'Kepala Bengkel', 'phone' => '0812']);

        Attendance::create([
            'worker_id' => $worker->id,
            'date' => now()->toDateString(),
            'check_in' => '08:00:00',
            'check_out' => '16:30:00',
        ]);

        $this->get(route('admin.attendances.index', ['month' => now()->format('Y-m')]))
            ->assertOk()
            ->assertSee('Dedi Kurniawan');
    }

    public function test_admin_can_generate_payroll_from_attendance_and_approve_creates_cashbook(): void
    {
        $this->actingAs($this->admin());
        $worker = Worker::create(['name' => 'Dedi Kurniawan', 'position' => 'Kepala Bengkel', 'phone' => '0812', 'salary' => 150000]);

        $monday = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $tuesday = Carbon::now()->startOfWeek(Carbon::MONDAY)->addDay()->toDateString();
        $wednesday = Carbon::now()->startOfWeek(Carbon::MONDAY)->addDays(2)->toDateString();

        Attendance::create([
            'worker_id' => $worker->id,
            'date' => $tuesday,
            'check_in' => '08:00:00',
            'check_out' => '16:30:00',
        ]);

        Attendance::create([
            'worker_id' => $worker->id,
            'date' => $wednesday,
            'check_in' => '08:00:00',
            'check_out' => '16:30:00',
        ]);

        $period = $monday;

        $this->post(route('admin.payrolls.generate'), ['period' => $period])
            ->assertRedirect(route('admin.payrolls.index', ['period' => $period]));

        $payroll = Payroll::where('worker_id', $worker->id)->where('period', $period)->firstOrFail();

        $this->assertEquals(2, $payroll->total_days);
        $this->assertEquals('draft', $payroll->status);
        $this->assertEquals(300000, (float) $payroll->salary_amount);

        $this->post(route('admin.payrolls.approve', $payroll))
            ->assertRedirect(route('admin.payrolls.index', ['period' => $period]));

        $this->assertDatabaseHas('payrolls', ['id' => $payroll->id, 'status' => 'approved']);
        $this->assertDatabaseHas('cashbooks', [
            'type' => 'pengeluaran',
            'amount' => 300000,
        ]);

        $cashbook = Cashbook::where('type', 'pengeluaran')->firstOrFail();
        $this->assertStringContainsString('Dedi Kurniawan', $cashbook->description);
        $this->assertStringContainsString($period, $cashbook->description);
    }

    public function test_admin_can_delete_draft_payroll_but_not_approved(): void
    {
        $this->actingAs($this->admin());
        $worker = Worker::create(['name' => 'Dedi Kurniawan', 'position' => 'Kepala Bengkel', 'phone' => '0812', 'salary' => 150000]);

        $draft = Payroll::create([
            'worker_id' => $worker->id,
            'period' => now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'),
            'total_days' => 1,
            'salary_amount' => 150000,
            'status' => 'draft',
        ]);

        $this->delete(route('admin.payrolls.destroy', $draft))
            ->assertRedirect(route('admin.payrolls.index', ['period' => $draft->period]));

        $this->assertDatabaseMissing('payrolls', ['id' => $draft->id]);

        $approved = Payroll::create([
            'worker_id' => $worker->id,
            'period' => now()->subWeek()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'),
            'total_days' => 1,
            'salary_amount' => 150000,
            'status' => 'approved',
            'approved_by' => $this->admin()->id,
            'approved_at' => now(),
        ]);

        $this->delete(route('admin.payrolls.destroy', $approved))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('payrolls', ['id' => $approved->id]);
    }

    public function test_public_products_only_use_produk_categories(): void
    {
        Category::factory()->create(['name' => 'Layanan Fabrikasi', 'slug' => 'fabrikasi', 'type' => 'layanan']);

        $this->get(route('home'))->assertOk()->assertDontSee('Layanan Fabrikasi');
    }
}
