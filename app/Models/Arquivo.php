<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    protected $fillable = [
        'cliente_id', 'tipo', 'caminho_do_arquivo', 'tamanho', 'duracao',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function agendamentos()
    {
        return $this->hasOne(Agendamento::class);
    }
    
}
