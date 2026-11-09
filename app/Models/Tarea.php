<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarea extends Model
{
    use SoftDeletes;

    protected $table = 'tareas';

    protected $fillable = ['asignacion_id', 'evaluacion_id', 'titulo', 'descripcion', 'fecha_entrega', 'avisar_familias', 'publicado_at'];

    protected function casts(): array
    {
        return [
            'fecha_entrega' => 'datetime',
            'publicado_at' => 'datetime',
            'avisar_familias' => 'boolean',
        ];
    }

    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(Asignacion::class);
    }

    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(Evaluacion::class);
    }

    public function adjuntos(): HasMany
    {
        return $this->hasMany(TareaAdjunto::class);
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class);
    }

    // Tareas publicadas cuya fecha de entrega todavía no pasó
    public function scopePendientes(Builder $query): Builder
    {
        return $query->whereNotNull('publicado_at')->where('fecha_entrega', '>=', now());
    }

    public function estaVencida(): bool
    {
        return $this->fecha_entrega->isPast();
    }
}