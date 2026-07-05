<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    protected $fillable = [
        'titulo', 'descricao', 'data_evento', 'hora_evento', 'local', 'cidade',
        'preco_ingresso', 'link_ingresso', 'ingressos_disponiveis', 'status', 'destaque', 'foto_cartaz'
    ];

    protected $casts = [
        'data_evento' => 'date',
        'preco_ingresso' => 'decimal:2',
        'destaque' => 'boolean',
    ];

    public function queries()
    {
        return $this->hasMany(ShowQuery::class, 'show_id');
    }
}
