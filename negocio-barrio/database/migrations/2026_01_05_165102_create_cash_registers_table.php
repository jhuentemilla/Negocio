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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('opening_balance', 12, 2)->default(0); // Saldo inicial
            $table->decimal('closing_balance', 12, 2)->nullable(); // Saldo final
            $table->decimal('expected_total', 12, 2)->nullable(); // Total esperado
            $table->decimal('difference', 12, 2)->nullable(); // Diferencia
            $table->enum('status', ['open', 'closed'])->default('open'); // Estado
            $table->text('notes')->nullable(); // Notas
            $table->dateTime('opened_at')->useCurrent(); // Hora de apertura
            $table->dateTime('closed_at')->nullable(); // Hora de cierre
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
