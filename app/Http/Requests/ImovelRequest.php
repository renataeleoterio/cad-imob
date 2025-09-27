<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImovelRequest extends FormRequest
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
        return [
            'tipo' => ['required', 'in:Terreno,Casa,Apartamento'],
            'area_terreno' => ['nullable', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'area_edificacao' => ['nullable', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'logradouro' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:20'],
            'bairro' => ['required', 'string', 'max:255'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'contribuinte_id' => ['required', 'exists:pessoas,id'],
            'situacao' => ['required', 'in:Ativo,Inativo'],
        ];
    }

    public function messages(): array {
        return [
            'tipo.required' => 'O campo Tipo é obrigatório.',
            'tipo.in' => 'O Tipo deve ser Terreno, Casa ou Apartamento.',
            'area_terreno.numeric' => 'Área do Terreno deve ser um número.',
            'area_terreno.regex' => 'Área do Terreno deve ter no máximo 2 casas decimais.',
            'area_edificacao.numeric' => 'Área da Edificação deve ser um número.',
            'area_edificacao.regex' => 'Área da Edificação deve ter no máximo 2 casas decimais.',
            'logradouro.required' => 'O Logradouro é obrigatório.',
            'numero.required' => 'O Número é obrigatório.',
            'bairro.required' => 'O Bairro é obrigatório.',
            'contribuinte_id.required' => 'Selecione um contribuinte.',
            'contribuinte_id.exists' => 'O contribuinte selecionado não existe.',
            'situacao.required' => 'O campo Situação é obrigatório.',
            'situacao.in' => 'A Situação deve ser Ativo ou Inativo.',
        ];
    }
}
