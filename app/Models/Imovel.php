<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Imovel extends Model
{
  use HasFactory;

  protected $table = "imoveis";

  protected $fillable = [
    'inscricao_municipal',
    "tipo",
    "area_terreno",
    "area_edificacao",
    'logradouro',
    'numero',
    'bairro',
    'complemento',
    'contribuinte_id',
    'situacao',
  ];

  public function contribuinte()
  {
    return $this->belongsTo(Pessoa::class, 'contribuinte_id');
  }

  public function documentos()
  {
    return $this->hasMany(Documento::class);
  }
  
  public function averbacoes(): HasMany
    {
        return $this->hasMany(Averbacao::class);
    }

    
    public function getSituacaoColorAttribute(): string
    {
        return match($this->situacao) {
            'Ativo' => 'success',
            'Inativo' => 'error',
            default => 'grey'
        };
    }

    public function getSituacaoIconAttribute(): string
    {
        return match($this->situacao) {
            'Ativo' => 'mdi-check-circle',
            'Inativo' => 'mdi-cancel',
            default => 'mdi-help-circle'
        };
    }
}