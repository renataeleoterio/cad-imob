<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::select('id', 'nome', 'email', 'perfil', 'ativo')
        ->paginate();
        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }

    
public function update(UpdateUserRequest $request, User $user)
{
    $data = $request->validated();

     $user->update([
        'nome' => $request->nome,
        'perfil' => $request->perfil,
        'ativo' => $request->ativo ? 'S' : 'N',
    ]);

      return redirect()->route('users.edit', $user->id)
        ->with('success', 'User atualizado com sucesso!');

}

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', compact('user'));
    }

    public function show(User $user)
{
    return Inertia::render('Users/Show', [
        'user' => $user
    ]);
}


}
