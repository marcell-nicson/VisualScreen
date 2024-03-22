<?php

namespace App\Repositories;

use App\Models\Arquivo;

class ArquivoRepository
{
    public function create(array $data)
    {
        return Arquivo::create($data);
    }

    public function update($id, array $data)
    {
        $arquivo = Arquivo::findOrFail($id);
        $arquivo->update($data);
        return $arquivo;
    }

    public function delete($id)
    {
        $arquivo = Arquivo::findOrFail($id);
        $arquivo->delete();
    }

    public function find($id)
    {
        return Arquivo::findOrFail($id);
    }

}
