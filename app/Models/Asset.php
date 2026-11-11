<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'asset_code',
        'type',
        'parent_id',
        'brand',
        'model',
        'serial_number',
        'location_id',
        'responsible_user_id',
        'status',
        'acquisition_date',
        'observations',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'type' => 'string',
        'status' => 'string',
        'acquisition_date' => 'date',
    ];

    /**
     * Activo padre (relación autorreferencial).
     * Ejemplo: un monitor (hijo) pertenece a una PC (padre).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'parent_id');
    }

    /**
     * Activos hijos (relación autorreferencial).
     * Ejemplo: los monitores/teclados conectados a una PC.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Asset::class, 'parent_id');
    }

    /**
     * Especificaciones técnicas del activo (relación 1 a 1).
     */
    public function specs(): HasOne
    {
        return $this->hasOne(AssetSpec::class);
    }

    /**
     * Tickets/incidentes asociados al activo.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Ubicación física del activo.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Usuario responsable del activo.
     */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
}
