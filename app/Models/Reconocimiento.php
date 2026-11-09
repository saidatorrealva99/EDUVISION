<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reconocimiento extends Model
{
    protected $table = 'reconocimientos';

    protected $fillable = ['estudiante_id', 'otorgado_por', 'tipo', 'descripcion', 'fecha'];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function otorgadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'otorgado_por');
    }
}