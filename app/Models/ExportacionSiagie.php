<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExportacionSiagie extends Model
{
    protected $table = 'exportaciones_siagie';

    const UPDATED_AT = null;

    protected $fillable = ['user_id', 'seccion_id', 'periodo_id', 'archivo', 'incompleta'];

    protected function casts(): array
    {
        return [
            'incompleta' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }
}