<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapeoSiagie extends Model
{
    protected $table = 'mapeo_siagie';

    public $timestamps = false;

    protected $fillable = ['anio_escolar_id', 'competencia_id', 'columna'];

    public function anioEscolar(): BelongsTo
    {
        return $this->belongsTo(AnioEscolar::class);
    }

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(Competencia::class);
    }
}