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
         Schema::create('carts', function (Blueprint $table) {
             $table->id('id_carrito');
             $table->foreignId('id_cliente')
                    ->constrained('customers', 'id_cliente')
                    ->onDelete('cascade');
             $table->string('direccion_envio',255);
             $table->string('cantidad_items');
             $table->decimal('envio_estimado',10,2)->default(0);
             $table->decimal('total',10,2);
             $table->timestamps();
         });
     }

     /**
      * Reverse the migrations.
      */
     public function down(): void
     {
         Schema::dropIfExists('carts');
     }
};
