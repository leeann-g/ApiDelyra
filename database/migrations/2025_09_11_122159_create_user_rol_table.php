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
        Schema::create('user_rol', function (Blueprint $table) {
            $table->foreignId('id_usuario')
                    ->constrained('users', 'id_usuario')
                    ->onDelete('cascade');
            $table->unsignedTinyInteger('id_rol');
            $table->foreign('id_rol')
                    ->references('id_rol')
                    ->on('rols')
                    ->onDelete('cascade');
            $table->primary(['id_usuario', 'id_rol']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rol');
    }
};
