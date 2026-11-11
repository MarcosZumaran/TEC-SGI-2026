<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlaEvent extends Model
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
        'ticket_id',
        'event_type',
        'escalation_level',
        'notified_to',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'event_type' => 'string',
        'escalation_level' => 'integer',
    ];

    /**
     * Ticket al que pertenece el evento de SLA.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Usuario notificado del evento.
     */
    public function notifiedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'notified_to');
    }
}
