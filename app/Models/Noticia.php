<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Noticia extends Model
{
    use SoftDeletes;

    protected $table = 'noticias';

    protected $fillable = ['titulo', 'slug', 'cuerpo', 'imagen', 'publicado_at'];

    protected function casts(): array
    {
        return [
            'publicado_at' => 'datetime',
        ];
    }

    // Genera el slug automáticamente a partir del título:
    // "Feria de Ciencias 2026" → "feria-de-ciencias-2026" (y "-2", "-3"... si ya existe)
    protected static function booted(): void
    {
        static::creating(function (Noticia $noticia) {
            if (empty($noticia->slug)) {
                $base = Str::slug($noticia->titulo);
                $slug = $base;
                $i = 2;
                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $noticia->slug = $slug;
            }
        });
    }

    // Las rutas usarán el slug en vez del id: /noticias/feria-de-ciencias-2026
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublicadas(Builder $query): Builder
    {
        return $query->whereNotNull('publicado_at')
            ->where('publicado_at', '<=', now())
            ->orderByDesc('publicado_at');
    }
}