<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recurso extends Model
{
    use SoftDeletes;

    protected $table = 'recursos';

    protected $fillable = ['curso_id', 'grado_id', 'titulo', 'tipo', 'url_o_archivo', 'publicado_por'];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class);
    }

    public function publicadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'publicado_por');
    }

    
}