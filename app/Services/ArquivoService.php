<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Repositories\ArquivoRepository;
use Exception;

class ArquivoService
{
    protected $arquivoRepository;

    public function __construct(ArquivoRepository $arquivoRepository)
    {
        $this->arquivoRepository = $arquivoRepository;
    }

    public function store(array $data)
    {
        try {
           
            $arquivo = $this->arquivoRepository->create([
                'cliente_id' => $data['cliente_id'],
                'tipo' => $data['tipo'],
                'caminho_do_arquivo' => $data['caminho_do_arquivo'], 
            ]);           

            $agendamento = $this->agendamentoRepository->create([
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

    // Outras funções do serviço aqui...
}
