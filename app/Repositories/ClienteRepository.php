<?php

namespace App\Repositories;

use App\Models\Cliente;

class ClienteRepository
{

    public function index()
    {
        return Cliente::all(); 
    }

    public function create(array $data)
    {
        return Cliente::create($data);
    }

    public function get($id)
    {
        return Cliente::findOrFail($id);
    }

    public function update($cliente, $request)
    {
        
        return $cliente->update($request->all());
        
    }

    public function delete(Cliente $cliente)
    {
        $cliente->delete();     
    }

}
