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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id_entrega');
            $table->foreignId('id_pedido')->constrained('orders', 'id_pedido')->onDelete('cascade');
            $table->foreignId('id_domiciliario')->constrained('delivery_people', 'id_domiciliario')->onDelete('set null');
            $table->string('direccion_envio',255);
            $table->boolean('estado')->default('');
            $table->dateTime('fecha_entrega')->useCurrent();
            $table->time('hora_estimada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
