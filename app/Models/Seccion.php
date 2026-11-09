<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seccion extends Model
{
    protected $table = 'secciones';

    protected $fillable = ['grado_id', 'anio_escolar_id', 'letra', 'capacidad', 'tutor_id'];

    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class);
    }

    public function anioEscolar(): BelongsTo
    {
        return $this->belongsTo(AnioEscolar::class);
    }

    // La columna se llama tutor_id, por eso se indica explícitamente
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'tutor_id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class);
    }

    // RN-11: ¿quedan vacantes?
    public function tieneCupo(): bool
    {
        return $this->matriculas()->where('estado', 'activa')->count() < $this->capacidad;
    }

    // Uso: $seccion->nombre_completo  →  "2.º B · Secundaria"
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->grado->nombre} {$this->letra} · {$this->grado->nivel->nombre}";
    }

        public function exportacionesSiagie(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExportacionSiagie::class);
    }
}