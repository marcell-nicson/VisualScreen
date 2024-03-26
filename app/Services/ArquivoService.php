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

            $datainicio = $request['DataHoraInicio'] ? $request['DataHoraInicio'] : $arquivo->agendamentos->DataHoraInicio;
            $datafim = $request['DataHoraFim'] ? $request['DataHoraFim'] : $arquivo->agendamentos->DataHoraFim;


            if($arquivo->agendamentos->DataHoraInicio !== $datainicio || $arquivo->agendamentos->DataHoraFim !== $datafim){

                dd('original' . $arquivo->agendamentos->DataHoraInicio . 'nova'. $datainicio ,'original' . $arquivo->agendamentos->DataHoraFim .'nova'. $datafim);

                $Inicio = Carbon::parse($datainicio);
                $Fim = Carbon::parse($datafim);
        
                if ($Fim <= $Inicio) {                
                    throw new Exception('A data de fim deve ser maior que a data de início.');                
                }
                $response = $arquivo->agendamentos->update([
                    'DataHoraInicio' => $datainicio,
                    'DataHoraFim' => $datafim,
                
                ]);
                if (!$response) {                
                    throw new Exception('Não foi possivel atualizar a data do arquivo');                
                }
                
                return true;             

            }  

            if ( $request['status']) {

                // $response = $this->agendamentoRepository->update($id , [
                //     'Status' => $request['status'],
                // ]);
                $response = $arquivo->agendamentos->update([
                    'Status' =>  $request['status'], 
                
                ]);

                if(!$response){
                    throw new Exception('Ocorreu um erro ao atualiazar o arquivo. Por favor, tente novamente.');                
                    
                }
                return true;               

            }

        } catch (Exception $e) {
            throw $e;
            
        }
        
    }



    // Outras funções do serviço aqui...
}
