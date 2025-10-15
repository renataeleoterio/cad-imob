<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImovelRequest extends FormRequest
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
            //
            'tipo' => 'required|in:Terreno,Casa,Apartamento',
            'area_terreno' => 'nullable|numeric|min:0',
            'area_edificacao' => 'nullable|numeric|min:0',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'contribuinte_id' => 'required|exists:pessoas,id',
            'situacao' => 'required|in:Ativo,Inativo',
        ];
    }
}
