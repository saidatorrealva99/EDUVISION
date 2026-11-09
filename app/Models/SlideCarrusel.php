<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SlideCarrusel extends Model
{
    protected $table = 'slides_carrusel';

    protected $fillable = ['imagen', 'titulo', 'subtitulo', 'enlace', 'orden', 'activo'];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    // Lo que muestra la página de inicio pública
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true)->orderBy('orden');
    }
}