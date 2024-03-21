<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nome', 'data_nascimento', 'telefone', 'email',
    ];

    // Se houver relacionamentos, você pode defini-los aqui
    public function arquivos()
    {
        return $this->hasMany(Arquivo::class);
    }
}
