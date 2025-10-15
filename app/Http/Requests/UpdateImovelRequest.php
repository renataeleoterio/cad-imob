<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateImovelRequest extends FormRequest
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

    public function withValidator(Validator $validator) {
        $validator->after(function (Validator $validator) {
             if (!$this->isPrecognitive() || !$this->header('Precognition-Validate-Only')) {
                $this->validateCustomRules($validator);
             }
            });
        }

        protected function validateCustomRules(Validator $validator){
            $data = $this->all();
            $tipo = $data['tipo'] ?? null;
            $areaTerreno = $data['area_terreno'] ?? null;
            $areaEdificacao = $data['area_edificacao'] ?? null;
        
            // terreno
            if ($tipo === 'Terreno') {
                if (empty($areaTerreno) || $areaTerreno === '' || $areaTerreno === null) {
                    $validator->errors()->add('area_terreno', 'Para terreno, a área do terreno é obrigatória.');
                } elseif ($areaTerreno <= 0) {
                    $validator->errors()->add('area_terreno', 'Para terreno, a área do terreno deve ser maior que zero.');
                }

                if ($areaEdificacao != 0 && $areaEdificacao !== null && $areaEdificacao !== '') {
                    $validator->errors()->add('area_edificacao', 'Para terreno, a área de edificação deve ser zero.');
                }
            }

            // casa
            if ($tipo === 'Casa') {
                if (empty($areaTerreno) || $areaTerreno === '' || $areaTerreno === null) {
                    $validator->errors()->add('area_terreno', 'Área de terreno para casa é obrigatória.');
                } elseif ($areaTerreno <= 0) {
                    $validator->errors()->add('area_terreno', 'Área de terreno para casa deve ser maior que zero.');
                }

                if (empty($areaEdificacao) || $areaEdificacao === '' || $areaEdificacao === null) {
                    $validator->errors()->add('area_edificacao', 'Área de edificação para casa é obrigatória.');
                } elseif ($areaEdificacao <= 0) {
                    $validator->errors()->add('area_edificacao', 'Área de edificação para casa deve ser maior que zero.');
                }
            }

            // apartamento
            if ($tipo === 'Apartamento') {
                if ($areaTerreno != 0 && $areaTerreno !== null && $areaTerreno !== '') {
                    $validator->errors()->add('area_terreno', 'Área do terreno, para apartamento, deve ser zero.');
                }

                if (empty($areaEdificacao) || $areaEdificacao === '' || $areaEdificacao === null) {
                    $validator->errors()->add('area_edificacao', 'Área de edificação, para apartamento, é obrigatória.');
                } elseif ($areaEdificacao <= 0) {
                    $validator->errors()->add('area_edificacao', 'Área de edificação, para apartamento, deve ser maior que zero.');
                }
            }
         }

    public function messages(): array
    {
        return [
            'tipo.required' => 'O tipo do imóvel é obrigatório',
            'tipo.in' => 'O tipo deve ser Terreno, Casa ou Apartamento',
            'area_terreno.numeric' => 'A área do terreno deve ser um número',
            'area_terreno.min' => 'A área do terreno não pode ser negativa',
            'area_edificacao.numeric' => 'A área da edificação deve ser um número',
            'area_edificacao.min' => 'A área da edificação não pode ser negativa',
            'logradouro.required' => 'O logradouro é obrigatório',
            'numero.required' => 'O número é obrigatório',
            'bairro.required' => 'O bairro é obrigatório',
            'contribuinte_id.required' => 'O contribuinte é obrigatório',
            'contribuinte_id.exists' => 'O contribuinte selecionado é inválido',
        ];
    }
}
