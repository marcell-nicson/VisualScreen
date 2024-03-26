<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequestUpdate;
use App\Http\Requests\ClienteRequestStore;
use App\Models\Cliente;
use App\Services\ClienteService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClienteController extends Controller
{
    protected $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    public function index()
    {                
        try {
            $clientes = $this->clienteService->index();
            return view('clientes.index', compact('clientes'));        
        } catch (Exception $e) {
            info($e);
            return redirect()->back()->with('error', 'Ocorreu um erro listar os clientes');
        }

    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(ClienteRequestStore $request)
    {
        try {
            $this->clienteService->create($request->all());
             return redirect()->route('clientes.index');

        } catch (Exception $e) {
            info($e);
            return redirect()->back()->with('error', 'Ocorreu um erro ao cadastrar o Cliente. Por favor, tente novamente.');
        }
    }

    public function show($id)
    {

        try {
            $cliente = $this->clienteService->get($id);
            return view('clientes.show', compact('cliente'));

        } catch (Exception $e) {
            info($e); 
            return redirect()->back()->with('error', 'Ocorreu um erro ao Visualizar o Cliente, tente novamente.');
        }

    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);       

        return view('clientes.edit', compact('cliente'));
    }

    public function update(ClienteRequestUpdate $request, $id)
    {
        try {
            
            $cliente = $this->clienteService->get($id);
            $this->clienteService->update($cliente, $request);
            return redirect()->route('clientes.index');
        } catch (Exception $e) {
            info($e);
            return redirect()->back()->with('error', 'Ocorreu um erro ao Editar o Cliente, tente novamente.');
        }

    }

    public function destroy(Cliente $cliente)
    {
        try {

            $this->clienteService->delete($cliente);
            
        } catch (Exception $e) {
            info($e);
            return redirect()->back()->with('error', 'Ocorreu um erro ao Ecluir o Cliente, tente novamente.');
        }

    }



}
