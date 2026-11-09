<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institucion extends Model
{
    protected $table = 'instituciones';

    protected $fillable = [
        'nombre', 'codigo_modular', 'direccion', 'telefono', 'email',
        'logo', 'color_primario', 'mision', 'vision',
    ];

    public function aniosEscolares(): HasMany
    {
        return $this->hasMany(AnioEscolar::class);
    }

    public function niveles(): HasMany
    {
        return $this->hasMany(Nivel::class);
    }
}