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
        Schema::create('cart_details', function (Blueprint $table) {
            $table->foreignId('id_carrito')
                    ->constrained('carts', 'id_carrito')
                    ->onDelete('cascade');
            $table->foreignId('id_producto')
                    ->constrained('products','id_producto')
                    ->onDelete('cascade');
            $table->decimal('precio_unitario');
            $table->unsignedInteger('cantidad');
            $table->primary(['id_carrito','id_producto']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_details');
    }
};
