<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apoderado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'apoderados';

    protected $fillable = ['user_id', 'telefono', 'direccion', 'consentimiento_datos_at'];

    protected function casts(): array
    {
        return [
            'consentimiento_datos_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Todos los hijos vinculados (activos o no)
    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(Estudiante::class, 'apoderado_estudiante')
                    ->withPivot('parentesco', 'es_principal', 'activo')
                    ->withTimestamps();
    }

    // Solo los hijos con vínculo activo: los únicos que el padre puede ver (RN-09)
    public function hijos(): BelongsToMany
    {
        return $this->estudiantes()->wherePivot('activo', true);
    }

    public function justificaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Justificacion::class);
    }
}
