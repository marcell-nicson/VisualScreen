<?php

namespace App\Repositories;

use App\Models\Agendamento;

class AgendamentoRepository
{
    public function create(array $data)
    {
        return Agendamento::create($data);
    }

    public function update($id, array $data)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->update($data);
        return $agendamento;
    }

    public function delete($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->delete();
    }

    public function find($id)
    {
        return Agendamento::findOrFail($id);
    }

}
