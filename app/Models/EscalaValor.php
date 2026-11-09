<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EscalaValor extends Model
{
    protected $table = 'escala_valores';

    public $timestamps = false;   // esta tabla no tiene created_at ni updated_at

    protected $fillable = ['escala_id', 'codigo', 'descripcion', 'minimo', 'maximo', 'orden'];

    protected function casts(): array
    {
        return [
            'minimo' => 'decimal:2',
            'maximo' => 'decimal:2',
        ];
    }

    public function escala(): BelongsTo
    {
        return $this->belongsTo(Escala::class);
    }
}