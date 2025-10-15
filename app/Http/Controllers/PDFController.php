<?php

namespace App\Http\Controllers;

use App\Models\Imovel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    //
    public function todosImoveis(PDF $pdf)
    {
        $imoveis = Imovel::with(['contribuinte'])
            ->get()
            ->map(function ($imovel) {
                // calcula área total da edificação
                $areaTotal = $this->calcularAreaTotal($imovel);
                
                return [
                    'inscricao_municipal' => $imovel->id,
                    'contribuinte' => $imovel->contribuinte->nome ?? 'N/A',
                    'tipo' => $imovel->tipo, 
                    'logradouro' => $imovel->logradouro,
                    'numero' => $imovel->numero,
                    'bairro' => $imovel->bairro,
                    'area_terreno' => $imovel->area_terreno,
                    'area_edificacao' => $areaTotal,
                    'situacao' => $imovel->situacao->nome ?? 'N/A',
                ];
            });

        $data = [
            'imoveis' => $imoveis,
            'titulo' => 'Relatório Geral de Imóveis',
            'dataGeracao' => now()->format('d/m/Y H:i:s'),
        ];

        return $pdf->loadView('pdf.todos-imoveis', $data)
            ->download('relatorio_geral_imoveis_' . now()->format('Y_m_d_His') . '.pdf');
    }

    // relatório individual
    public function imovelIndividual($id, PDF $pdf)
    {
        $imovel = Imovel::with(['contribuinte', 'averbacoes'])
            ->findOrFail($id);

        $areaTotal = $this->calcularAreaTotal($imovel);

        $data = [
            'imovel' => $imovel,
            'areaTotal' => $areaTotal,
            'titulo' => 'Relatório Individual de Imóvel',
            'dataGeracao' => now()->format('d/m/Y H:i:s'),
        ];

        return $pdf->loadView('pdf.imovel-individual', $data)
            ->download('relatorio_imovel_' . $imovel->id . '_' . now()->format('Y_m_d_His') . '.pdf');
    }

    // metodo para calcular área total
    private function calcularAreaTotal($imovel)
    {
        $areaBase = $imovel->area_edificacao ?? 0;
        $reducoes = $imovel->reducoes_area ?? 0;
        $aumentos = $imovel->aumentos_area ?? 0;
        
        return $areaBase - $reducoes + $aumentos;
    }
}
