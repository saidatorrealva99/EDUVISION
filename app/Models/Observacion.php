<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Observacion extends Model
{
    use SoftDeletes;

    protected $table = 'observaciones';

    protected $fillable = ['matricula_id', 'docente_id', 'texto', 'visible_familia', 'requiere_atencion'];

    protected function casts(): array
    {
        return [
            'visible_familia' => 'boolean',
            'requiere_atencion' => 'boolean',
        ];
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }
}