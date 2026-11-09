<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TareaAdjunto extends Model
{
    protected $table = 'tarea_adjuntos';

    const UPDATED_AT = null;

    protected $fillable = ['tarea_id', 'archivo', 'nombre_original', 'mime', 'tamano_kb'];

    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }

    // Uso: $adjunto->url  →  "/storage/tareas/ficha-pag12.pdf"
    public function getUrlAttribute(): string
    {
        return Storage::url($this->archivo);
    }
}