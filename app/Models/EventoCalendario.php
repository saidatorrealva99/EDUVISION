<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoCalendario extends Model
{
    protected $table = 'eventos_calendario';

    protected $fillable = ['titulo', 'tipo', 'inicio', 'fin', 'alcance_tipo', 'alcance_id'];

    protected function casts(): array
    {
        return [
            'inicio' => 'datetime',
            'fin' => 'datetime',
        ];
    }
}