<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meta extends Model
{
    protected $table = 'metas';

    protected $fillable = ['matricula_id', 'creado_por', 'descripcion', 'estado', 'fecha_objetivo'];

    protected function casts(): array
    {
        return [
            'fecha_objetivo' => 'date',
        ];
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}