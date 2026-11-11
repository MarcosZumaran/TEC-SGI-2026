<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketComment extends Model
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
        'user_id',
        'message',
        'is_internal',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'is_internal' => 'boolean',
    ];

    /**
     * Ticket al que pertenece el comentario.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Autor del comentario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
