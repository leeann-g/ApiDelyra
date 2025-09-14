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
        Schema::create('traders', function (Blueprint $table) {
            $table->id('id_comerciante');
            $table->foreignId('id_usuario');
            $table->unsignedTinyInteger('id_rol');
            $table->foreign(['id_usuario','id_rol'])
                    ->references(['id_usuario','id_rol'])
                    ->on('user_rols')
                    ->onDelete('cascade');
            $table->string('nombre_local');
            $table->string('cuenta_bancaria',30);
            $table->string('nit',30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traders');
    }
};
