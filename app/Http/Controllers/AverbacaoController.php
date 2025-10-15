<?php

namespace App\Http\Controllers;

use App\Models\Averbacao;
use App\Models\Imovel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AverbacaoController extends Controller
{
    //

        public function store(Request $request, Imovel $imovel)
    {
        $validated = $request->validate([
            'evento' => 'required|in:aumento_area_construida,reducao_area_construida,observacao,cancelamento,reativacao',
            'medida' => 'required_if:evento,aumento_area_construida,reducao_area_construida|nullable|numeric|min:0.01',
            'descricao' => 'required|string|max:1000',
        ], [
            'medida.required_if' => 'O campo medida é obrigatório para este evento.',
            'medida.min' => 'A medida deve ser maior que zero.',
        ]);

        return DB::transaction(function () use ($validated, $imovel) {
            // validações específicas por evento
            if ($validated['evento'] === 'cancelamento' && $imovel->situacao === 'Inativo') {
                throw ValidationException::withMessages([
                    'evento' => 'Não é possível cancelar um imóvel já inativo.'
                ]);
            }

            if ($validated['evento'] === 'reativacao' && $imovel->situacao === 'Ativo') {
                throw ValidationException::withMessages([
                    'evento' => 'Não é possível reativar um imóvel já ativo.'
                ]);
            }

            $averbacao = $imovel->averbacoes()->create([
                'evento' => $validated['evento'],
                'medida' => $validated['medida'],
                'descricao' => $validated['descricao'],
                'data' => now(),
            ]);

            // atualizar imóvel conforme evento
            $this->aplicarEventoImovel($imovel, $validated['evento'], $validated['medida'] ?? 0);

            return redirect()->back()
                ->with('success', 'Averbação registrada com sucesso!');
        });
    }

    public function destroy(Imovel $imovel, Averbacao $averbacao)
    {
        DB::transaction(function () use ($averbacao, $imovel) {
            // Reverter alterações no imóvel
            $this->reverterEventoImovel($imovel, $averbacao);
            $averbacao->delete();
        });

        return redirect()->back()
            ->with('success', 'Averbação excluída com sucesso!');
    }

    private function aplicarEventoImovel(Imovel $imovel, string $evento, float $medida = 0): void
    {
        switch ($evento) {
            case 'aumento_area_construida':
                $imovel->update([
                    'area_edificacao' => $imovel->area_edificacao + $medida
                ]);
                break;

            case 'reducao_area_construida':
                $imovel->update([
                    'area_edificacao' => max(0, $imovel->area_edificacao - $medida)
                ]);
                break;

            case 'cancelamento':
                $imovel->update(['situacao' => 'Inativo']);
                break;

            case 'reativacao':
                $imovel->update(['situacao' => 'Ativo']);
                break;
        }
    }

    private function reverterEventoImovel(Imovel $imovel, Averbacao $averbacao): void
    {
        switch ($averbacao->evento) {
            case 'aumento_area_construida':
                $imovel->update([
                    'area_edificacao' => max(0, $imovel->area_edificacao - $averbacao->medida)
                ]);
                break;

            case 'reducao_area_construida':
                $imovel->update([
                    'area_edificacao' => $imovel->area_edificacao + $averbacao->medida
                ]);
                break;

            case 'cancelamento':
                $imovel->update(['situacao' => 'Ativo']);
                break;

            case 'reativacao':
                $imovel->update(['situacao' => 'Inativo']);
                break;
        }
    }
}
    

