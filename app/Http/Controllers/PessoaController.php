<?php

namespace App\Http\Controllers;

use App\Http\Requests\PessoaRequest;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class PessoaController extends Controller
{
    // exibir a lista com paginação de pessoas
    public function index(Request $request)
    {
        $pessoas = Pessoa::Paginate(10);

        $query = Pessoa::query();


        return Inertia::render(
            'Pessoas/Index',
            [
                'pessoas' => $pessoas->items(),
                'totalPessoas' => $pessoas->total(),
            ]
        );
    }

    // exibe o formulario para criar uma nova pessoa
    public function create()
    {
        return Inertia::render('Pessoas/Create');
    }

    // armazenar uma nova pessoa no banco de dados
    public function store(PessoaRequest $request)
    {
        
        // salvar no banco de dados
        Pessoa::create($request->validated());

        // redireciona para a pagina inicial após o cadastro
        return redirect()->route('pessoas.index')
        ->with('success', 'Pessoa criada com sucesso!');
    }

    // exibir formulário para editar uma pessoa existente
    public function edit(Pessoa $pessoa)
    {
        return Inertia::render('Pessoas/Edit', ['pessoa' => $pessoa]);
    }

    // atualizar uma pessoa existente no banco de dados
    public function update(PessoaRequest $request, Pessoa $pessoa)
    {
        $pessoa->update($request->validated());
        
        return redirect()->route('pessoas.index')
        ->with('success', 'Pessoa atualizada com sucesso!');
    }

    // excluir uma pessoa do banco de dados
    public function destroy(Pessoa $pessoa)
    {
        $pessoa->delete();
        return redirect()->route('pessoas.index')->with('success', 'Pessoa deletada com sucesso!');
    }
}
