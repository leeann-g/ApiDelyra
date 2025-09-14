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
        Schema::create('delivery_people', function (Blueprint $table) {
            $table->id('id_domiciliario');
            $table->foreignId('id_usuario');
            $table->unsignedTinyInteger('id_rol');
            $table->foreign(['id_usuario','id_rol'])
                    ->references(['id_usuario','id_rol'])
                    ->on('user_rols')
                    ->onDelete('cascade');
            $table->boolean('estado_dis')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_people');
    }
};
