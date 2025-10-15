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

        if ($this->isMethod('GET')) {
            return $this->filterRules();
        }
        
        return $this->storeUpdateRules();
    }
 protected function filterRules(): array
    {
        return [
            'tipo' => 'nullable|string|in:Terreno,Casa,Apartamento',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'bairro' => 'nullable|string|max:255',
            'contribuinte_id' => 'nullable|integer|exists:pessoas,id',
            'situacao' => 'nullable|string|in:Ativo,Inativo',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }


    public function messages(): array
    {
        return [
            // mensagens para filtros
            'tipo.in' => 'O tipo deve ser Terreno, Casa ou Apartamento',
            'contribuinte_id.exists' => 'O contribuinte selecionado não existe',
            'situacao.in' => 'A situação deve ser Ativo ou Inativo',
            
            // mensagens para store/update
            'tipo.required' => 'O tipo do imóvel é obrigatório',
            'logradouro.required' => 'O logradouro é obrigatório',
            'numero.required' => 'O número é obrigatório',
            'bairro.required' => 'O bairro é obrigatório',
            'contribuinte_id.required' => 'O contribuinte é obrigatório',
        ];
    }
    }