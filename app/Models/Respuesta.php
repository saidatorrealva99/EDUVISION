<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Sin estudiante_id: nadie puede saber quién respondió qué (RN-07)
class Respuesta extends Model
{
    protected $table = 'respuestas';

    public $timestamps = false;

    protected $fillable = ['encuesta_id', 'asignacion_id', 'pregunta_id', 'valor', 'texto', 'visible', 'fecha'];

    protected function casts(): array
    {
        return [
            'visible' => 'boolean',
            'fecha' => 'date',
        ];
    }

    public function encuesta(): BelongsTo
    {
        return $this->belongsTo(Encuesta::class);
    }

    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(Asignacion::class);
    }

    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class);
    }

    // Comentarios abiertos ya aprobados por moderación (RN-08)
    public function scopeVisibles(Builder $query): Builder
    {
        return $query->where('visible', true);
    }
}