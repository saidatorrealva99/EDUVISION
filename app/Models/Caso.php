<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caso extends Model
{
    protected $table = 'casos';

    protected $fillable = ['matricula_id', 'responsable_id', 'origen', 'prioridad', 'estado', 'resultado', 'abierto_at', 'cerrado_at'];

    protected function casts(): array
    {
        return [
            'abierto_at' => 'datetime',
            'cerrado_at' => 'datetime',
        ];
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function acciones(): HasMany
    {
        return $this->hasMany(CasoAccion::class)->latest('fecha');
    }

    // Casos que no están cerrados
    public function scopeAbiertos(Builder $query): Builder
    {
        return $query->where('estado', '!=', 'cerrado');
    }

    // RN-10: cerrar exige un resultado
    public function cerrar(string $resultado): void
    {
        $this->update([
            'estado' => 'cerrado',
            'resultado' => $resultado,
            'cerrado_at' => now(),
        ]);
    }

        public function citaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Citacion::class);
    }
}