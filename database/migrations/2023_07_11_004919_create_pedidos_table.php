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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->dateTime("fecha");
            $table->integer("estado")->default(1);
            $table->bigInteger("cliente_id")->unsigned();
            $table->bigInteger("user_id")->unsigned();

            // N:1
            $table->foreign("cliente_id")->references("id")->on("clientes");
            $table->foreign("user_id")->references("id")->on("users");
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
