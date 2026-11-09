<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comunicado extends Model
{
    use SoftDeletes;

    protected $table = 'comunicados';

    protected $fillable = ['autor_id', 'titulo', 'cuerpo', 'alcance_tipo', 'alcance_id', 'requiere_confirmacion', 'publicado_at'];

    protected function casts(): array
    {
        return [
            'requiere_confirmacion' => 'boolean',
            'publicado_at' => 'datetime',
        ];
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function lecturas(): HasMany
    {
        return $this->hasMany(ComunicadoLectura::class);
    }

    // Solo los ya publicados (los que tienen fecha de publicación pasada)
    public function scopePublicados(Builder $query): Builder
    {
        return $query->whereNotNull('publicado_at')->where('publicado_at', '<=', now());
    }
}