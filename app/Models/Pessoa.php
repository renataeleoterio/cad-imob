<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Pessoa extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
    

    protected $fillable = [
        'nome',
        'cpf',
        'data_nascimento',
        'sexo',
        'email',
        'telefone',
    ];
}
