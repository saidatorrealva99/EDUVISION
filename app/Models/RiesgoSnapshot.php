<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiesgoSnapshot extends Model
{
    protected $table = 'riesgo_snapshots';

    const UPDATED_AT = null;   // la foto del día no se edita

    protected $fillable = ['matricula_id', 'fecha', 'puntaje', 'nivel', 'motivos'];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'motivos' => 'array',
        ];
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }
}