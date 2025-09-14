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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id('id_vehiculo');
            $table->foreignId('id_domiciliario')
                    ->constrained('delivery_people', 'id_domiciliario')
                    ->onDelete('cascade');
            $table->string('placa', 10)->unique();
            $table->string('tipo_vehiculo',255);
            $table->date('run_vigente');
            $table->date('seguro_vigente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};