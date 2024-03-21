<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequestUpdate extends FormRequest
{
    public function authorize()
    {
        return true; // Aqui você pode adicionar a lógica de autorização, se necessário
    }

    public function rules()
    {
        $clienteId = $this->route('cliente');
        
        return [
            'nome' => 'required|max:255',
            'data_nascimento' => 'required|date',
            'telefone' => 'required',
            "email" => "required|email|unique:clientes,email,{$clienteId},id",
            
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.max' => 'O campo nome não pode ter mais que :max caracteres.',
            'data_nascimento.required' => 'O campo data de nascimento é obrigatório.',
            'data_nascimento.date' => 'O campo data de nascimento deve ser uma data válida.',
            'telefone.required' => 'O campo telefone é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email fornecido não é válido.',
            'email.unique' => 'Este email já está em uso.',
        ];
    }
}

