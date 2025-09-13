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
            Schema::create('orders', function (Blueprint $table) {
                $table->id('id_pedido');
                $table->foreignId('id_cliente')->constrained('customers', 'id_cliente')->onDelete('cascade');
                $table->date('fecha_pedido');
                $table->string('estado');
                $table->decimal('total', 10,2);
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
