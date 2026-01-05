<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'card', 'transfer'])->default('cash')->after('total');
            $table->foreignId('cash_register_id')->nullable()->constrained('cash_registers')->nullOnDelete()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropForeignIdFor(\App\Models\CashRegister::class);
        });
    }
};
