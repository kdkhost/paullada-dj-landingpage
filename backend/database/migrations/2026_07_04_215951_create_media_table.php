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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('nome_original');
            $table->string('nome_armazenado');
            $table->string('caminho');
            $table->string('tipo'); // imagem, video, audio, documento
            $table->string('mime_type');
            $table->unsignedBigInteger('tamanho');
            $table->string('pasta')->default('uploads');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
