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
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_producto');
            $table->foreignId('id_categoria')
                    ->constrained('categories','id_categoria')
                    ->onDelete('cascade');
            $table->foreignId('id_comerciante')
                    ->constrained('traders','id_comerciante')
                    ->onDelete('cascade');
            $table->string('nombre_producto',100);
            $table->decimal('precio',10,2);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
