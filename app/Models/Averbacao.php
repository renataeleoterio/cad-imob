<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Averbacao extends Model
{
    use HasFactory;

    protected $table = 'averbacaos';
    
    protected $fillable = [
        'imovel_id',
        'evento',
        'medida',
        'descricao',
        'data'
    ];

    protected $casts = [
        'data' => 'date',
        'medida' => 'decimal:2'
    ];

     public function imovel(): BelongsTo
    {
        return $this->belongsTo(Imovel::class);
    }

    
    public function getEventoLabelAttribute(): string
    {
        return match($this->evento) {
            'aumento_area_construida' => 'Aumento Área Construída',
            'reducao_area_construida' => 'Redução Área Construída',
            'observacao' => 'Observação',
            'cancelamento' => 'Cancelamento',
            'reativacao' => 'Reativação',
            default => $this->evento
        };
    }


    public function getEventoColorAttribute(): string
    {
        return match($this->evento) {
            'aumento_area_construida' => 'success',
            'reducao_area_construida' => 'warning',
            'observacao' => 'info',
            'cancelamento' => 'error',
            'reativacao' => 'success',
            default => 'grey'
        };
    }


     public function getEventoIconAttribute(): string
    {
        return match($this->evento) {
            'aumento_area_construida' => 'mdi-arrow-up-bold',
            'reducao_area_construida' => 'mdi-arrow-down-bold',
            'observacao' => 'mdi-text-box-outline',
            'cancelamento' => 'mdi-cancel',
            'reativacao' => 'mdi-restore',
            default => 'mdi-help-circle'
        };
    }
}
