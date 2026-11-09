<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorreccionNota extends Model
{
    protected $table = 'correcciones_nota';

    protected $fillable = [
        'nota_id', 'valor_anterior', 'valor_nuevo', 'motivo',
        'solicitado_por', 'aprobado_por', 'estado', 'revisado_at',
    ];

    protected function casts(): array
    {
        return [
            'revisado_at' => 'datetime',
        ];
    }

    public function nota(): BelongsTo
    {
        return $this->belongsTo(Nota::class);
    }

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitado_por');
    }

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}