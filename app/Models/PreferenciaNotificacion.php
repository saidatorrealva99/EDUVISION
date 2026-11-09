<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreferenciaNotificacion extends Model
{
    protected $table = 'preferencias_notificacion';

    // RF-62: estos avisos no se pueden desactivar
    public const OBLIGATORIOS = ['falta', 'citacion'];

    protected $fillable = ['user_id', 'tipo', 'canal', 'activo'];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function esObligatoria(): bool
    {
        return in_array($this->tipo, self::OBLIGATORIOS);
    }
}