<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnioEscolar extends Model
{
    protected $table = 'anios_escolares';

    // activo_unico NO va aquí: lo calcula MySQL solo
    protected $fillable = ['institucion_id', 'anio', 'fecha_inicio', 'fecha_fin', 'estado'];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    public function periodos(): HasMany
    {
        return $this->hasMany(Periodo::class)->orderBy('orden');
    }

    public function secciones(): HasMany
    {
        return $this->hasMany(Seccion::class);
    }

    // Uso: AnioEscolar::activo()->first()
    public function scopeActivo(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }
}
