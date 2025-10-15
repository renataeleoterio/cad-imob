<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImovelRequest;
use App\Http\Requests\StoreImovelRequest;
use App\Http\Requests\UpdateImovelRequest;
use App\Models\Documento;
use App\Models\Imovel;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ImovelController extends Controller
{
    public function index(Request $request) {
        $query = Imovel::with('contribuinte'); 
        
        
        if($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        };

        if($request->filled('situacao')) {
            $query->where('situacao', $request->situacao);
        }; 

        if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('logradouro', 'like', "%{$search}%")
              ->orWhere('bairro', 'like', "%{$search}%");
        });
    }

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
    
    public function store(StoreImovelRequest $request) {
        Imovel::create($request->validated());

        return redirect()->route('imoveis.index')
        ->with('success','Imóvel criado com sucesso!');
    }

    public function edit(Imovel $imovel) {
        
        return Inertia::render('Imoveis/Edit', [
            'imovel' => $imovel->load('averbacoes'),
            'pessoas' => Pessoa::all(),
            'averbacoes' => $imovel->averbacoes,
        ]);

        
    }

    public function update(UpdateImovelRequest $request, Imovel $imovel) 
    {
        $imovel->update($request->validated());

        return redirect()->route('imoveis.index')
        ->with('success','Imóvel atualizado com sucesso.');
    }


    public function inativar(Imovel $imovel) {
        $imovel->situacao = 'Inativo';
        $imovel->save();

        return back()->with('success', 'Imovel inativado com sucesso!');
    }

    /**
     * Upload de documentos
     */

    public function storeDocumento(Request $request, Imovel $imovel) 
    {
        $request->validate([
            'documentos.*' => 'file|max:3072|mimes:jpg,jpeg,png,pdf',
        ]);
    
        foreach ($request->file('documentos', []) as $file) {
            $path = $file->store('documentos', 'public');

            $imovel->documentos()->create([
                'nome' => $file->getClientOriginalName(),
                'tipo' => $file->getClientOriginalExtension(),
                'tamanho' => $file->getSize(),
                'caminho' => $path,
            ]);
        }

        return back()->with('success', 'Documentos enviados com sucesso!');
    }


    /**
     * Excluir documento especifico de um imovel
     */

    public function destroyDocumento(Imovel $imovel, Documento $documento) 
    {
        if ($documento->imovel_id !== $imovel->id) {
            abort(403, 'Documento não pertence a este imovel.');
        }
        return Storage::download($documento->caminho, $documento->nome);
    }

}
