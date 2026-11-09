<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grado extends Model
{
    protected $table = 'grados';

    public $timestamps = false;

    protected $fillable = ['nivel_id', 'nombre', 'orden'];

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class);
    }

    public function secciones(): HasMany
    {
        return $this->hasMany(Seccion::class)->orderBy('letra');
    }

    public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class, 'curso_grado')
                    ->withPivot('horas_semanales');
    }

        public function recursos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Recurso::class);
    }
}