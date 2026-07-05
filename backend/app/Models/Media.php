<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    protected $fillable = [
        'nome_original',
        'nome_armazenado',
        'caminho',
        'tipo',
        'mime_type',
        'tamanho',
        'pasta',
        'usuario_id',
    ];

    protected $appends = ['url', 'tamanho_formatado'];

    public function getUrlAttribute(): string
    {
        return '/storage/' . $this->caminho;
    }

    public function getTamanhoFormatadoAttribute(): string
    {
        $bytes = $this->tamanho;
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
