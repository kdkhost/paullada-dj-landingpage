<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = ['tipo', 'titulo', 'descricao', 'arquivo', 'url_youtube', 'ordem', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
