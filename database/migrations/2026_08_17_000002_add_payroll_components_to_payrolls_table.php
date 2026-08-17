<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('bonus', 12, 2)->default(0)->after('salary_amount');
            $table->decimal('lemburan', 12, 2)->default(0)->after('bonus');
            $table->decimal('uang_luar_kota', 12, 2)->default(0)->after('lemburan');
            $table->decimal('kasbon', 12, 2)->default(0)->after('uang_luar_kota');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['bonus', 'lemburan', 'uang_luar_kota', 'kasbon']);
        });
    }
};
