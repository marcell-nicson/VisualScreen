<?php

namespace App\Services;

use App\Repositories\ClienteRepository;
use Exception;

class ClienteService
{
    protected $clienteRepository;

    public function __construct(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function index()
    {

        try {           
           return $this->clienteRepository->index();
        } catch (Exception $e) {
            throw $e;
        }        
        
    }
        
    public function create($request)
    {
        try {
            return $this->clienteRepository->create($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get($id)
    {
        try {
            return $this->clienteRepository->get($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update($cliente, $request)
    {
        try {
            return $this->clienteRepository->update($cliente, $request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete($cliente) : ? bool
    {
        try {
            
           return $cliente = $this->clienteRepository->delete($cliente);

        } catch (Exception $e) {
            throw $e;
        }
    }

}
