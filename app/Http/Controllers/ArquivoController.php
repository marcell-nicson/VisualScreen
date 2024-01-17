<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Arquivo;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArquivoController extends Controller
{
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

        $nome = $cliente->nome;

        return view('arquivos.index', compact('arquivos', 'nome'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $clientes = Cliente::all();
        return view('arquivos.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
                 
            $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'tipo' => 'required|in:video,foto,link',
                'caminho_do_arquivo' => ($request->tipo == 'link') ? 'required|url' : 'required|mimes:mp4,jpg,jpeg,png',                
                'DataHoraInicio' => 'required|date_format:Y-m-d\TH:i',
                'DataHoraFim' => 'required|date_format:Y-m-d\TH:i',
                'Status' => 'required|in:ativo,inativo,pausado',
            ]);
           
            $arquivo = New Arquivo();
            
            $arquivo->cliente_id = $request->input('cliente_id');
            $arquivo->tipo = $request->input('tipo');

            if ($request->tipo == 'foto' || $request->File('caminho_do_arquivo')) {
                
                $foto = $request->file('caminho_do_arquivo');
                $nomeFoto = time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('fotos'), $nomeFoto);
                $arquivo->caminho_do_arquivo = $nomeFoto;
                $arquivo->save(); 
            } elseif ($request->tipo == 'video' || $request->hasFile('caminho_do_arquivo')) {

                $video = $request->file('caminho_do_arquivo');
                
                // Defina um nome único para o arquivo
                $nomeArquivo = uniqid().'.'.$video->getClientOriginalExtension();
        
                // Mova o arquivo para o diretório desejado
                $video->move(public_path('videos'), $nomeArquivo);        
             
                $arquivo->caminho_do_arquivo = $nomeArquivo;
                $arquivo->save(); 
        
            } else {

                $videoUrl = $request->input('caminho_do_arquivo');
                preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $matches);
                $videoId = isset($matches[1]) ? $matches[1] : null;
                $arquivo->caminho_do_arquivo = $videoId;
                $arquivo->save();
            }

            Agendamento::create([
                'arquivo_id' => $arquivo->id,
                'Status' => $request->input('Status'),
                'DataHoraInicio' => $request->input('DataHoraInicio'),
                'DataHoraFim' => $request->input('DataHoraFim'),
            ]);
            $idCliente = $arquivo->cliente_id;
            
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

            $originalinicio = $arquivo->agendamentos->DataHoraInicio;
            $originalfim = $arquivo->agendamentos->DataHoraFim;        
            $datainicio = $request->DataHoraInicio ? $request->DataHoraInicio : $originalinicio;
            $datafim = $request->DataHoraFim ? $request->DataHoraFim : $originalfim;

            if($originalinicio !== $datainicio or $originalfim !== $datafim){

                $Inicio = \Carbon\Carbon::parse($datainicio);
                $Fim = \Carbon\Carbon::parse($datafim);
        
                if ($Fim <= $Inicio) {
                    return redirect()->back()->with('error', 'A data de fim deve ser maior que a data de início.');
                }

                $arquivo->agendamentos->update([
                    'DataHoraInicio' => $datainicio,
                    'DataHoraFim' => $datafim,                                  
                ]);

                return redirect()->back();
            }
        
            $statusOptions = ['inativo', 'pausado', 'ativo'];
            
            if (in_array($request->status, $statusOptions)) {

                Agendamento::where('arquivo_id', $arquivo->id)->update(['Status' => $request->status]);

                return redirect()->back();
            }

            if ($request->tipo == 'foto') {
                    
                $foto = $request->file('caminho_do_arquivo');
                $nomeFoto = time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('fotos'), $nomeFoto);
                $arquivo->caminho_do_arquivo = $nomeFoto;
                $arquivo->save(); 
            } elseif ($request->tipo == 'video') {
                $caminhoDoArquivo = Storage::putFile('/public', $request->file('caminho_do_arquivo'));

                $arquivo->caminho_do_arquivo = $caminhoDoArquivo;
                $arquivo->save();            
            } else {

                $videoUrl = $request->input('caminho_do_arquivo');
                preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $matches);
                $videoId = isset($matches[1]) ? $matches[1] : null;
                $arquivo->caminho_do_arquivo = $videoId;
                $arquivo->save();
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
