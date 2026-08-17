<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['belum', 'dp', 'lunas'])->default('belum')->after('warranty_end_date');
            $table->decimal('payment_amount', 12, 2)->nullable()->after('payment_status');
            $table->string('payment_type')->nullable()->after('payment_amount');
            $table->date('payment_date')->nullable()->after('payment_type');
            $table->string('payment_proof')->nullable()->after('payment_date');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_amount', 'payment_type', 'payment_date', 'payment_proof']);
        });
    }
};
