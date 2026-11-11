<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    /**
     * La tabla solo tiene created_at definido en la base de datos.
     */
    public $timestamps = false;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'ip_address',
    ];

    /**
     * Conversiones de tipos: los JSON se manejan como arreglos PHP.
     */
    protected $casts = [
        'action' => 'string',
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Usuario que realizó la acción auditada.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
