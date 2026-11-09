<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Nota extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'notas';

    protected $fillable = ['evaluacion_id', 'matricula_id', 'valor_literal', 'valor_numerico', 'registrado_por'];

    protected function casts(): array
    {
        return [
            'valor_numerico' => 'decimal:2',
        ];
    }

    // Auditoría (RF-12): guarda el valor anterior y el nuevo de cada cambio
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('notas')
            ->logOnly(['valor_literal', 'valor_numerico'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(Evaluacion::class);
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function correcciones(): HasMany
    {
        return $this->hasMany(CorreccionNota::class);
    }

    // Uso: $nota->valor  →  "A" o "15.50", según la escala
    public function getValorAttribute(): ?string
    {
        return $this->valor_literal ?? $this->valor_numerico;
    }
}