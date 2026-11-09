<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    protected $table = 'cursos';

    public $timestamps = false;

    protected $fillable = ['nivel_id', 'nombre', 'abreviatura'];

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class);
    }

    public function competencias(): HasMany
    {
        return $this->hasMany(Competencia::class)->orderBy('orden');
    }

    public function grados(): BelongsToMany
    {
        return $this->belongsToMany(Grado::class, 'curso_grado')
                    ->withPivot('horas_semanales');
    }

        public function asignaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Asignacion::class);
    }

    public function recursos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Recurso::class);
    }
}