<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComunicadoLectura extends Model
{
    protected $table = 'comunicado_lecturas';

    public $timestamps = false;

    protected $fillable = ['comunicado_id', 'user_id', 'leido_at', 'confirmado_at'];

    protected function casts(): array
    {
        return [
            'leido_at' => 'datetime',
            'confirmado_at' => 'datetime',
        ];
    }

    public function comunicado(): BelongsTo
    {
        return $this->belongsTo(Comunicado::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}