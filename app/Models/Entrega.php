<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrega extends Model
{
    protected $table = 'entregas';

    protected $fillable = ['tarea_id', 'matricula_id', 'archivo', 'texto', 'entregado_at', 'fuera_de_plazo', 'calificacion'];

    protected function casts(): array
    {
        return [
            'entregado_at' => 'datetime',
            'fuera_de_plazo' => 'boolean',
        ];
    }

    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }
}