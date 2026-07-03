<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['nome', 'url', 'icone', 'ordem', 'ativo'])]
class SocialLink extends Model
{
    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }
}
