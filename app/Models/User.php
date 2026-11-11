<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Campos ocultos en serializaciones (JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversiones de tipos.
     * - 'password' => 'hashed' encripta automáticamente con Hash::make().
     * - 'role' se mantiene como string con los valores del ENUM.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'string',
    ];

    /**
     * Tickets que el usuario reportó como solicitante.
     */
    public function reportedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'reporter_id');
    }

    /**
     * Tickets asignados al usuario como técnico.
     */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'technician_id');
    }

    /**
     * Activos de los cuales el usuario es responsable.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'responsible_user_id');
    }

    /**
     * Problemas creados por el usuario.
     */
    public function createdProblems(): HasMany
    {
        return $this->hasMany(Problem::class, 'created_by');
    }

    /**
     * Problemas asignados al usuario.
     */
    public function assignedProblems(): HasMany
    {
        return $this->hasMany(Problem::class, 'assigned_to');
    }

    /**
     * Comentarios realizados por el usuario.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }
}
