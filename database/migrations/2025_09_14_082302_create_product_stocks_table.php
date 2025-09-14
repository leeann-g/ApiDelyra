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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id('id_inventario');
            $table->foreignId('id_product')
                    ->constrained('products','id_producto')
                    ->onDelete('cascade');
            $table->foreignId('id_sucursal')
                    ->constrained('branches','id_sucursal')
                    ->onDelete('cascade');
            $table->unsignedInteger('cantidad');
            $table->string('ubicacion',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
