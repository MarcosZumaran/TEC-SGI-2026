<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'site_id',
        'code',
        'name',
    ];

    /**
     * Sede a la que pertenece la ubicación.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Activos ubicados en esta ubicación.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Tickets reportados en esta ubicación.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
