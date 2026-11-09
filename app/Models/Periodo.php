<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Periodo extends Model
{
    protected $table = 'periodos';

    protected $fillable = ['anio_escolar_id', 'nombre', 'orden', 'fecha_inicio', 'fecha_fin', 'estado'];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function anioEscolar(): BelongsTo
    {
        return $this->belongsTo(AnioEscolar::class);
    }

    public function estaAbierto(): bool
    {
        return $this->estado === 'abierto';
    }

        public function evaluaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Evaluacion::class);
    }

    public function exportacionesSiagie(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExportacionSiagie::class);
    }
}