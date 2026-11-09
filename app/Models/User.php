<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'dni',
        'nombres',
        'apellidos',
        'email',
        'password',
        'activo',
        'debe_cambiar_password',
        'ultimo_acceso',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'ultimo_acceso' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
            'debe_cambiar_password' => 'boolean',
        ];
    }

    public function docente(): HasOne
    {
        return $this->hasOne(Docente::class);
    }

    public function estudiante(): HasOne
    {
        return $this->hasOne(Estudiante::class);
    }

    public function apoderado(): HasOne
    {
        return $this->hasOne(Apoderado::class);
    }

    // Uso: $user->nombre_completo  →  "Rosa Quispe Huamán"
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }

        public function preferenciasNotificacion(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PreferenciaNotificacion::class);
    }

    // Comunicados que este usuario escribió
    public function comunicados(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comunicado::class, 'autor_id');
    }

    // Comunicados que este usuario ya leyó
    public function lecturas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ComunicadoLectura::class);
    }

    // Casos de seguimiento a su cargo (tutor, psicólogo, coordinador)
    public function casosAsignados(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Caso::class, 'responsable_id');
    }

    public function exportacionesSiagie(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExportacionSiagie::class);
    }
}