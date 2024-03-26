<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArquivoRequest;
use App\Models\Agendamento;
use App\Models\Arquivo;
use App\Models\Cliente;
use App\Services\ArquivoService;
use App\Services\ClienteService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArquivoController extends Controller
{
    protected $arquivoService;
    
    protected $clienteService;

    public function __construct(ArquivoService $arquivoService, ClienteService $clienteService)
    {
        $this->arquivoService = $arquivoService;
        $this->clienteService = $clienteService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        $clienteId = $request->input('id');
        $arquivoId = $request->query('id');

        $arquivos = $this->arquivoService->all($arquivoId);

        $cliente = $this->clienteService->get($clienteId);
        
        if (!$cliente) {
            abort(404, 'Cliente não encontrado');
        }        

        return view('arquivos.index', compact('arquivos', 'cliente'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        
        $clienteId = $request->input('cliente');
        $cliente = Cliente::find($clienteId);
       
        if (!$cliente) {
            abort(404, 'Cliente não encontrado');
        } 

        return view('arquivos.create', compact('cliente'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArquivoRequest $request)
    {
        try {


            $arquivo = $this->arquivoService->store($request->all());
            
            return redirect()->route('arquivos.index', ['id' => $arquivo->cliente_id])->with('success', 'Arquivo cadastrado com sucesso!');
           
        } catch (\Exception $e) {            
                    
            dd($e , $request->all());                 
            return redirect()->back()->with('error', 'Ocorreu um erro ao cadastrar o arquivo. Por favor, tente novamente.');
        }
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        try {
            $this->arquivoService->update($request->all(), $id);

            return redirect()->back();
        } catch (\Exception $e) {                          
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function destroy(Arquivo $arquivo)
    {
        $arquivo->delete();

        return redirect()->back();
    }
}
