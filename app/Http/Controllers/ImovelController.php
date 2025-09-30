<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImovelRequest;
use App\Models\Imovel;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImovelController extends Controller
{
    public function index(Request $request) {
        $query = Imovel::with('contribuinte'); 
        
        
        if($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        };

        $imoveis = $query->paginate(10)
        ->appends($request->all());

        return Inertia::render("Imoveis/Index", [
            'imoveis' => $imoveis,
            'filters' => $request->all() ?? [],
        ]);
    }

    public function create() {
        return Inertia::render('Imoveis/Create', [
            'pessoas'=> Pessoa::orderBy('nome')->get(),
            'tipos' =>['Terreno', 'Casa', 'Apartamento'],
            'situacoes' => ['Ativo', 'Inativo'],
            ]);
    }
    
    public function store(ImovelRequest $request) {
        Imovel::create($request->validated());

        return redirect()->route('imoveis.index')
        ->with('success','Imóvel criado com sucesso!');
    }

    public function edit(Imovel $imovel) {
        return Inertia::render('Imoveis/Edit', [
            'imovel' => $imovel,
            'pessoas' => Pessoa::orderBy('nome')->get(),
        ]);
    }

    public function update(ImovelRequest $request, Imovel $imovel) 
    {
        $imovel->update($request->validated());

        return redirect()->route('imoveis.index')
        ->with('success','Imóvel atualizado com sucesso.');
    }

    public function destroy(Imovel $imovel) {
        $imovel->update([
            'situacao' => 'Inativo',
        ]);

        return redirect()->route('imoveis.index')
        ->with('success','Imovel inativado com sucesso.');
    }
}
