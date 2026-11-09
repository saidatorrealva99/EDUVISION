<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Escala extends Model
{
    protected $table = 'escalas';

    protected $fillable = ['nombre', 'tipo'];

    public function valores(): HasMany
    {
        return $this->hasMany(EscalaValor::class)->orderBy('orden');
    }

    public function niveles(): HasMany
    {
        return $this->hasMany(Nivel::class);
    }
}