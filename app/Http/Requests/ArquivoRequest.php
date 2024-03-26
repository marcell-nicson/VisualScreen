<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArquivoRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'tipo' => 'required|in:video,foto,link',
            'caminho_do_arquivo' => ($this->tipo == 'link') ? 'required|url' : 'required|mimes:mp4,jpg,jpeg,png',                
            'DataHoraInicio' => 'required|date_format:Y-m-d\TH:i',
            'DataHoraFim' => 'required|date_format:Y-m-d\TH:i',
            'Status' => 'required|in:ativo,inativo,pausado',
        ];
    }

    public function messages()
    {
        return [
            'tipo.required' => 'O campo tipo é obrigatório.',
            'tipo.in' => 'O campo tipo deve ser video, foto ou link.',
            'caminho_do_arquivo.required' => 'O campo caminho do arquivo é obrigatório.',
            'caminho_do_arquivo.url' => 'O caminho do arquivo deve ser uma URL válida.',
            'caminho_do_arquivo.mimes' => 'O arquivo deve ser um vídeo (MP4) ou uma imagem (JPEG, JPG, PNG).',
            'DataHoraInicio.required' => 'O campo DataHoraInicio é obrigatório.',
            'DataHoraInicio.date_format' => 'O campo DataHoraInicio deve estar no formato Y-m-d\TH:i.',
            'DataHoraFim.required' => 'O campo DataHoraFim é obrigatório.',
            'DataHoraFim.date_format' => 'O campo DataHoraFim deve estar no formato Y-m-d\TH:i.',
            'Status.required' => 'O campo Status é obrigatório.',
            'Status.in' => 'O campo Status deve ser ativo, inativo ou pausado.',
        ];
    }
}
