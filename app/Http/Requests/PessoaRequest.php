<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PessoaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $pessoaId = $this->pessoa?->id;
 
        return [
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required','string', 'max:14', 
            Rule::unique('pessoas','cpf')->ignore($pessoaId),
            ],
            'data_nascimento' => ['required', 'date'],
            'sexo' => ['required', 'in:Masculino,Feminino,Outro'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:pessoas'],
            'telefone' => ['nullable', 'string', 'max:15'],
        ];
    }
    
}
