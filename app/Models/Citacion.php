<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Citacion extends Model
{
    protected $table = 'citaciones';

    protected $fillable = ['matricula_id', 'caso_id', 'citado_por', 'fecha_hora', 'motivo', 'estado', 'respuesta'];

    protected function casts(): array
    {
        return [
            'fecha_hora' => 'datetime',
        ];
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function caso(): BelongsTo
    {
        return $this->belongsTo(Caso::class);
    }

    public function citadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'citado_por');
    }
}