<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ReglaRiesgo extends Model
{
    protected $table = 'reglas_riesgo';

    protected $fillable = ['codigo', 'nombre', 'parametros', 'puntos', 'activa'];

    protected function casts(): array
    {
        return [
            'parametros' => 'array',   // el JSON se lee y guarda como arreglo PHP
            'activa' => 'boolean',
        ];
    }

    // Uso: ReglaRiesgo::activas()->get()
    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('activa', true);
    }
}