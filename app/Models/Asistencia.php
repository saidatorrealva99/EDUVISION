<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Asistencia extends Model
{
    use LogsActivity;

    protected $table = 'asistencias';

    protected $fillable = ['matricula_id', 'asignacion_id', 'fecha', 'estado', 'metodo', 'registrado_por'];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    // Auditoría: registra los cambios de estado (por ejemplo, F → FJ)
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('asistencias')
            ->logOnly(['estado'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(Asignacion::class);
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function justificaciones(): HasMany
    {
        return $this->hasMany(Justificacion::class);
    }
}