<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asignacion extends Model
{
    protected $table = 'asignaciones';

    protected $fillable = ['docente_id', 'curso_id', 'seccion_id', 'anio_escolar_id'];

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    public function anioEscolar(): BelongsTo
    {
        return $this->belongsTo(AnioEscolar::class);
    }

    public function evaluaciones(): HasMany
    {
        return $this->hasMany(Evaluacion::class);
    }

    // Uso: $asignacion->nombre  →  "Matemática · 2.º B · Secundaria"
    public function getNombreAttribute(): string
    {
        return "{$this->curso->nombre} · {$this->seccion->nombre_completo}";
    }

        public function asistencias(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function tareas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Tarea::class);
    }

    public function participaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Participacion::class);
    }

    public function respuestas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Respuesta::class);
    }
}