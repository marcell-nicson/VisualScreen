<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $fillable = [
        'arquivo_id', 'DataHoraInicio', 'DataHoraFim', 'Status',
    ];

    public function arquivo()
    {
        return $this->belongsTo(Arquivo::class);
    }
}


