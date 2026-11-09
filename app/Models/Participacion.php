<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Solo registra QUE el estudiante ya respondió (evita duplicados).
// No tiene ninguna relación con Respuesta: así se garantiza el anonimato (RN-07).
class Participacion extends Model
{
    protected $table = 'participaciones';

    public $timestamps = false;

    protected $fillable = ['encuesta_id', 'estudiante_id', 'asignacion_id'];

    public function encuesta(): BelongsTo
    {
        return $this->belongsTo(Encuesta::class);
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(Asignacion::class);
    }
}