<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoMatricula extends Model
{
    protected $table = 'movimientos_matricula';

    // La tabla solo tiene created_at: un movimiento no se edita
    const UPDATED_AT = null;

    protected $fillable = ['matricula_id', 'tipo', 'seccion_anterior_id', 'motivo', 'fecha', 'registrado_por'];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function seccionAnterior(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_anterior_id');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}