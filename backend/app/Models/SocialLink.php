<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = ['nome', 'url', 'icone', 'ordem', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
