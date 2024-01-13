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

            // dd($request->all());
            
            // Validação dos dados do formulário
            $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'tipo' => 'required|in:video,foto,link',
                'caminho_do_arquivo' => ($request->tipo == 'link') ? 'required|url' : 'required|mimes:mp4,jpg,jpeg,png',                
                'DataHoraInicio' => 'required|date_format:Y-m-d\TH:i',
                'DataHoraFim' => 'required|date_format:Y-m-d\TH:i',
                'Status' => 'required|in:ativo,inativo,pausado',
            ]);

            // Criação do arquivo
            $arquivo = New Arquivo();
            
            $arquivo->cliente_id = $request->input('cliente_id');
            $arquivo->tipo = $request->input('tipo');


            if ($request->tipo == 'video' || $request->tipo == 'foto') {
                // Certifique-se de ajustar a lógica de armazenamento conforme necessário
                $caminhoDoArquivo = Storage::putFileAs(
                    '/uploads', // Substitua '/uploads' pelo diretório desejado dentro de storage/app/VisualScreen
                    $request->file('caminho_do_arquivo'),
                    'foto.' . $request->file('caminho_do_arquivo')->extension()
                );
                // dd($caminhoDoArquivo);
                $arquivo->caminho_do_arquivo = $caminhoDoArquivo;
                $arquivo->save();            
            } else {

                $videoUrl = $request->input('caminho_do_arquivo');
                preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $matches);
                $videoId = isset($matches[1]) ? $matches[1] : null;
                $arquivo->caminho_do_arquivo = $videoId;
                $arquivo->save();
            }

            // Criação do agendamento associado ao arquivo
            Agendamento::create([
                'arquivo_id' => $arquivo->id,
                'Status' => $request->input('Status'),
                'DataHoraInicio' => $request->input('DataHoraInicio'),
                'DataHoraFim' => $request->input('DataHoraFim'),
            ]);
            $idCliente = $arquivo->cliente_id;

            // Redireciona de volta para a página de arquivos ou para onde você desejar
            return redirect()->route('arquivos.index', ['id' => $idCliente])->with('success', 'Arquivo cadastrado com sucesso!');
           
        } catch (\Throwable $th) {
            // Log do erro para referência futura
            \Log::error($th);
        
            // Imprime mais detalhes da exceção
            dd($th);
        
            // Redireciona de volta para a página anterior com mensagem de erro
            return redirect()->back()->with('error', 'Ocorreu um erro ao cadastrar o arquivo. Por favor, tente novamente.');
        }
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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

        Agendamento::where('arquivo_id', $id)->update(['Status' => $request->status,]);

        return redirect()->back();
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
