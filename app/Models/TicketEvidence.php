<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketEvidence extends Model
{
    use HasFactory;

    /**
     * Nombre real de la tabla (Laravel la pluralizaría mal).
     */
    protected $table = 'ticket_evidences';

    /**
     * La tabla solo tiene created_at definido en la base de datos.
     */
    public $timestamps = false;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'ticket_id',
        'file_path',
        'file_type',
    ];

    /**
     * Ticket al que pertenece la evidencia.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
