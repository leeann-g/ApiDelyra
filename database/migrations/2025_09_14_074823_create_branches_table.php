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
        Schema::create('branches', function (Blueprint $table) {
            $table->id('id_sucursal');
            $table->foreignId('id_comerciante')
                    ->constrained('traders','id_comerciante')
                    ->onDelete('cascade');
            $table->string('nombre_sucursal',50);
            $table->string('direccion',255);
            $table->string('latitud',255);
            $table->string('longitud',255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
