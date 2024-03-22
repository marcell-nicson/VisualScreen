<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArquivoRequest;
use App\Models\Agendamento;
use App\Models\Arquivo;
use App\Models\Cliente;
use App\Services\ArquivoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArquivoController extends Controller
{
    protected $arquivoService;

    public function __construct(ArquivoService $arquivoService)
    {
        $this->arquivoService = $arquivoService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        $id = $request->input('id');

        $arquivos = Arquivo::where('cliente_id', $request->query('id'))->with('agendamentos')->get();
        $cliente = Cliente::find($id);
        
        
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
                 
           $this->arquivoService->store($request->all());

            
            return redirect()->route('arquivos.index', ['id' => $idCliente])->with('success', 'Arquivo cadastrado com sucesso!');
           
        } catch (\Exception $e) {            
            info($e);            
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
        
            $arquivo = Arquivo::where('id', $id)->with('agendamentos')->first();

            $originalinicio = $arquivo->agendamentos->DataHoraInicio ? $arquivo->agendamentos->DataHoraInicio : null;
            $originalfim = $arquivo->agendamentos->DataHoraFim ? $arquivo->agendamentos->DataHoraFim : null;   
            $datainicio = $request->DataHoraInicio ? $request->DataHoraInicio : $originalinicio;
            $datafim = $request->DataHoraFim ? $request->DataHoraFim : $originalfim;

            // dd($arquivo,  'DataHoraInicio: '. $originalinicio, 'DataHoraFim: '. $originalfim, 'DataHoraInicio: '. $datainicio, 'DataHoraFim: '. $datafim );

            if($originalinicio !== $datainicio || $originalfim !== $datafim){

                $Inicio = Carbon::parse($datainicio);
                $Fim = Carbon::parse($datafim);
        
                if ($Fim <= $Inicio) {
                    return redirect()->back()->with('error', 'A data de fim deve ser maior que a data de início.');
                }

                $arquivo->agendamentos->update([
                    'DataHoraInicio' => $datainicio,
                    'DataHoraFim' => $datafim,                                  
                ]);

                return redirect()->back();
            }
        
           
            
            if ($request->status) {

                Agendamento::where('arquivo_id', $arquivo->id)->update(['Status' => $request->status]);

                return redirect()->back();
            }

           

        } catch (\Exception $e) {            
            info($e);            
            dd($e , $request->all());                 
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualiazar o arquivo. Por favor, tente novamente.');
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
