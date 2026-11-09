<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluacion extends Model
{
    use SoftDeletes;

    protected $table = 'evaluaciones';

    protected $fillable = ['asignacion_id', 'competencia_id', 'periodo_id', 'nombre', 'tipo', 'peso', 'fecha'];

    protected function casts(): array
    {
        return [
            'peso' => 'decimal:2',
            'fecha' => 'date',
        ];
    }

    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(Asignacion::class);
    }

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(Competencia::class);
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    // Tareas vinculadas a esta evaluación (opcional: una tarea puede calificarse aquí)
    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }
}