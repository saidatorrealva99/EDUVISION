<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matricula extends Model
{
    use SoftDeletes;

    protected $table = 'matriculas';

    protected $fillable = ['estudiante_id', 'seccion_id', 'anio_escolar_id', 'fecha', 'estado'];

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

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    public function anioEscolar(): BelongsTo
    {
        return $this->belongsTo(AnioEscolar::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoMatricula::class);
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    public function observaciones(): HasMany
    {
        return $this->hasMany(Observacion::class);
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function riesgoSnapshots(): HasMany
    {
        return $this->hasMany(RiesgoSnapshot::class);
    }

    // El semáforo de hoy: la foto más reciente
    public function riesgoActual(): HasOne
    {
        return $this->hasOne(RiesgoSnapshot::class)->latestOfMany('fecha');
    }

    public function casos(): HasMany
    {
        return $this->hasMany(Caso::class);
    }

    // Uso: Matricula::activas()->get()
    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('estado', 'activa');
    }

        public function citaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Citacion::class);
    }

    public function entregas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Entrega::class);
    }

    public function metas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Meta::class);
    }
}