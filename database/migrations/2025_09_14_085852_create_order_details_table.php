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
        Schema::create('order_details', function (Blueprint $table) {
            $table->foreignId('id_pedido')
                    ->constrained('orders', 'id_pedido')
                    ->onDelete('cascade');
            $table->foreignId('id_producto')
                    ->constrained('products', 'id_producto')
                    ->onDelete('cascade');
            $table->string('cantidad_productos');
            $table->decimal('precio_unitario');
            $table->decimal('total',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
