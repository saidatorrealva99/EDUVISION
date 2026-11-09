<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Docente extends Model
{
    use SoftDeletes;

    protected $table = 'docentes';

    protected $fillable = ['user_id', 'especialidad'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Secciones donde este docente es tutor
    public function seccionesTutoria(): HasMany
    {
        return $this->hasMany(Seccion::class, 'tutor_id');
    }

    // Cursos que dicta (la base de sus permisos)
    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class);
    }

        public function observaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Observacion::class);
    }
}