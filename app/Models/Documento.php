<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

protected $fillable = [
        'imovel_id',
        'nome',
        'tipo',
        'tamanho',
        'caminho',
    ];

    public function imovel()
    {
        return $this->belongsTo(Imovel::class);
    }
}