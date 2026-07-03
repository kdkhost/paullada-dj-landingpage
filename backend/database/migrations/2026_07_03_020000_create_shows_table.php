<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->date('data_evento');
            $table->string('hora_evento');
            $table->string('local');
            $table->string('cidade');
            $table->decimal('preco_ingresso', 10, 2)->nullable();
            $table->string('link_ingresso')->nullable();
            $table->integer('ingressos_disponiveis')->nullable();
            $table->enum('status', ['active', 'inactive', 'done'])->default('active');
            $table->boolean('destaque')->default(false);
            $table->string('foto_cartaz')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shows');
    }
};
