<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Competencia extends Model
{
    protected $table = 'competencias';

    public $timestamps = false;

    protected $fillable = ['curso_id', 'nombre', 'orden'];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

        public function evaluaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Evaluacion::class);
    }

    public function mapeosSiagie(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MapeoSiagie::class);
    }
}