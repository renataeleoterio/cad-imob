<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Audit extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_type',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    public function scopeEvent($query, $event)
    {
        return $query->where('event', $event);
    }

    public function scopeAuditableType($query, $type)
    {
        return $query->where('auditable_type', $type);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function getEventNameAttribute(): string
    {
        return match($this->event) {
            'created' => 'Criação',
            'updated' => 'Alteração',
            'deleted' => 'Exclusão',
            default => $this->event,
        };
    }

    public function getTableNameAttribute(): string
    {
        return match($this->auditable_type) {
            'App\\Models\\Imovel' => 'Imóveis',
            'App\\Models\\Pessoa' => 'Contribuintes',
            'App\\Models\\Averbacao' => 'Averbações',
            'App\\Models\\User' => 'Usuários',
            default => class_basename($this->auditable_type),
        };
    }

}
