<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Audit::with('user');


       if ($request->filled('usuario')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->usuario . '%');
            });
        }

        if ($request->filled('evento')) {
            $query->where('event', $request->evento);
        }

        if ($request->filled('tabela')) {
            $query->where('auditable_type', 'like', '%' . $request->tabela . '%');
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $query->orderBy($sortBy, $sortOrder);

        $audits = $query->paginate($request->get('per_page', 20));

       
        $usuarios = User::pluck('nome', 'id');
        $eventosOptions = [
            ['title' => 'Criação', 'value' => 'created'],
            ['title' => 'Alteração', 'value' => 'updated'],
            ['title' => 'Exclusão', 'value' => 'deleted']
        ];
        
        $tabelas = Audit::distinct('auditable_type')->pluck('auditable_type');

        return inertia('Audit/Index', [
            'audits' => $audits,
            'filters' => $request
            ->only(['usuario', 'evento', 'tabela', 'data_inicio', 'data_fim']),
            'usuarios' => $usuarios,
            'eventos' => $eventosOptions,
            'tabelas' => $tabelas,
        ]);
    }

   
    public function show($id)
    {
        $audit = Audit::with('user')
            ->findOrFail($id);

        return inertia('Audit/Show', [
            'audit' => $audit,
        ]);
    }

      private function getTableName($auditableType)
    {
        $models = [
            'App\\Models\\Imovel' => 'Imóveis',
            'App\\Models\\Contribuinte' => 'Contribuintes',
            'App\\Models\\Averbacao' => 'Averbações',
            'App\\Models\\User' => 'Usuários',
        ];
        return $models[$auditableType] ?? class_basename($auditableType);
    }
}
