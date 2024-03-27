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

        try {
            
            $arquivo = $this->arquivoRepository->find($id);   
            $datarequesinicio = Carbon::createFromFormat('Y-m-d\TH:i', $request['DataHoraInicio']); 
            $datarequestfim = Carbon::createFromFormat('Y-m-d\TH:i', $request['DataHoraFim']); 

            $datainicio = $request['DataHoraInicio'] ? $datarequesinicio->format('Y-m-d H:i:s') : $arquivo->agendamentos->DataHoraInicio;
            $datafim = $request['DataHoraFim'] ? $datarequestfim->format('Y-m-d H:i:s') : $arquivo->agendamentos->DataHoraFim;

            $data = [];

            if($arquivo->agendamentos->DataHoraInicio != $datainicio || $arquivo->agendamentos->DataHoraFim != $datafim){
                $Inicio = Carbon::parse($datainicio);
                $Fim = Carbon::parse($datafim);
        
                if ($Fim <= $Inicio) {                
                    throw new Exception('A data de fim deve ser maior que a data de início.');                
                }
                $data = [
                    'DataHoraInicio' => $datainicio,
                    'DataHoraFim' => $datafim,
                ]; 
                
            }  
            
            if ($request['status'] != $arquivo->agendamentos->Status) {
                $data = ['Status' =>  $request['status']];
                
            }

            if(!$data){
                throw new Exception('Ocorreu um erro ao atualizar o arquivo. Por favor, tente novamente.');          
            }
            $arquivo->agendamentos->update($data);


        } catch (Exception $e) {
            throw $e;
            
        }
        
    }



    // Outras funções do serviço aqui...
}
