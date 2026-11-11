<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetSpec extends Model
{
    use HasFactory;

    /**
     * La tabla no tiene columnas updated_at/created_at en el ER.
     */
    public $timestamps = false;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'asset_id',
        'processor',
        'ram',
        'disk',
        'os',
        'ip_address',
        'mac_address',
    ];

    /**
     * Activo al que pertenecen las especificaciones.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
