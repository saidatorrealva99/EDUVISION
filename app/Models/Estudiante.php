<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Estudiante extends Model
{
    use SoftDeletes;

    protected $table = 'estudiantes';

    protected $fillable = ['user_id', 'dni', 'nombres', 'apellidos', 'fecha_nacimiento', 'sexo', 'foto'];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
        ];
    }

    // Genera el código QR del carné automáticamente al crear el estudiante
    protected static function booted(): void
    {
        static::creating(function (Estudiante $estudiante) {
            $estudiante->codigo_qr ??= Str::random(32);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function apoderados(): BelongsToMany
    {
        return $this->belongsToMany(Apoderado::class, 'apoderado_estudiante')
                    ->withPivot('parentesco', 'es_principal', 'activo')
                    ->withTimestamps();
    }

    // Todas sus matrículas (una por año): su historial
    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    // La matrícula del año escolar activo
    public function matriculaActual(): HasOne
    {
        return $this->hasOne(Matricula::class)
                    ->whereHas('anioEscolar', fn ($q) => $q->where('estado', 'activo'));
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->apellidos}, {$this->nombres}";
    }

        public function participaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Participacion::class);
    }

    public function reconocimientos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reconocimiento::class)->latest('fecha');
    }
}