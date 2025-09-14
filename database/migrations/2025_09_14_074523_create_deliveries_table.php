<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

 return new class extends Migration
 {
     public function up(): void
     {
         Schema::create('deliveries', function (Blueprint $table) {
             $table->id('id_entrega');
             $table->foreignId('id_pedido')
                     ->constrained('orders', 'id_pedido')
                     ->onDelete('cascade');
            $table->foreignId('id_domiciliario')
                    ->nullable()
                    ->constrained('delivery_people', 'id_domiciliario')
                    ->onDelete('set null');
             $table->string('direccion_envio',255);
             $table->boolean('estado')->default(false);
             $table->timestamp('fecha_entrega')->useCurrent();
             $table->time('hora_estimada')->nullable();
             $table->timestamps();
         });
     }

     public function down(): void
     {
         Schema::dropIfExists('deliveries');
     }
 };