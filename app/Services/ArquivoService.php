<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Repositories\ArquivoRepository;
use App\Repositories\AgendamentoRepository;
use Carbon\Carbon;
use Exception;

class ArquivoService
{
    protected $arquivoRepository;
    protected $agendamentoRepository;

    public function __construct(ArquivoRepository $arquivoRepository, AgendamentoRepository $agendamentoRepository)
    {
        $this->arquivoRepository = $arquivoRepository;
        $this->agendamentoRepository = $agendamentoRepository;
    }

    public function get($id)
    {
        return $this->arquivoRepository->find($id); 
    }


    public function all($id)
    {
        return $this->arquivoRepository->all($id); 
    }

    public function store(array $data)
    {
        try {   
           
            $arquivo = $this->arquivoRepository->create([
                'cliente_id' => $data['cliente'],
                'tipo' => $data['tipo'],
                'caminho_do_arquivo' => $data['caminho_do_arquivo'], 
            ]);           

            $this->agendamentoRepository->create([
                'arquivo_id' => $arquivo->id,
                'Status' => $data['Status'],
                'DataHoraInicio' => $data['DataHoraInicio'],
                'DataHoraFim' => $data['DataHoraFim'],
            ]);            
           
            return $arquivo;
        } catch (Exception $e) {            
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $arquivo = $this->arquivoRepository->find($id);        

        $originalinicio = $arquivo->agendamentos->DataHoraInicio ? $arquivo->agendamentos->DataHoraInicio : null;
        $originalfim = $arquivo->agendamentos->DataHoraFim ? $arquivo->agendamentos->DataHoraFim : null;   
        $datainicio = $request->DataHoraInicio ? $request->DataHoraInicio : $originalinicio;
        $datafim = $request->DataHoraFim ? $request->DataHoraFim : $originalfim;


        if($originalinicio !== $datainicio || $originalfim !== $datafim){

            $Inicio = Carbon::parse($datainicio);
            $Fim = Carbon::parse($datafim);
    
            if ($Fim <= $Inicio) {
                return false;
            }

            $response = $arquivo->agendamentos->update([
                'DataHoraInicio' => $datainicio,
                'DataHoraFim' => $datafim,
               
            ]);

            if($response){
                return true;
            }

        }    
        
        if ($request->status) {         

            $response = $this->agendamentoRepository->update($id , [
                'Status' => $request->status,
            ]);

            if($response){
                return true;
            }

        }
    }



    // Outras funções do serviço aqui...
}
