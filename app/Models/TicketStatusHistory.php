<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketStatusHistory extends Model
{
    use HasFactory;

    /**
     * Nombre real de la tabla (Laravel la pluralizaría mal).
     */
    protected $table = 'ticket_status_history';

    /**
     * La tabla solo tiene created_at definido en la base de datos.
     */
    public $timestamps = false;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'ticket_id',
        'old_status',
        'new_status',
        'changed_by',
    ];

    /**
     * Ticket al que pertenece el cambio de estado.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Usuario que realizó el cambio.
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
