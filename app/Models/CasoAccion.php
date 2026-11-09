<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CasoAccion extends Model
{
    protected $table = 'caso_acciones';

    protected $fillable = ['caso_id', 'user_id', 'tipo', 'descripcion', 'fecha'];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
        ];
    }

    public function caso(): BelongsTo
    {
        return $this->belongsTo(Caso::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}