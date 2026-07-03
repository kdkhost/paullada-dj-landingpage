<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['nome', 'email', 'telefone', 'mensagem', 'show_id', 'lido'])]
class ShowQuery extends Model
{
    protected function casts(): array
    {
        return [
            'lido' => 'boolean',
        ];
    }

    public function show()
    {
        return $this->belongsTo(Show::class, 'show_id');
    }
}
