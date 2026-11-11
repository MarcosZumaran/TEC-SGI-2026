<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Problem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'title',
        'description',
        'root_cause',
        'workaround',
        'status',
        'created_by',
        'assigned_to',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Usuario que registró el problema.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Técnico asignado al problema.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Tickets/incidentes relacionados con este problema.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
