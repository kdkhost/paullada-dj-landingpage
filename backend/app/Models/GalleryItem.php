<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['tipo', 'titulo', 'descricao', 'arquivo', 'url_youtube', 'ordem', 'ativo'])]
class GalleryItem extends Model
{
    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }
}
