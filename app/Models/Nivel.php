<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nivel extends Model
{
    protected $table = 'niveles';

    protected $fillable = ['institucion_id', 'nombre', 'escala_id'];

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    public function escala(): BelongsTo
    {
        return $this->belongsTo(Escala::class);
    }

    public function grados(): HasMany
    {
        return $this->hasMany(Grado::class)->orderBy('orden');
    }

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }

        public function encuestas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Encuesta::class);
    }
}