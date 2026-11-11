<?php

namespace App\Models;

use App\Services\SlaScheduleSynchronizer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use JeffersonGoncalves\ServiceDesk\Contracts\SlaCalculator;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'ticket_number',
        'reporter_id',
        'technician_id',
        'asset_id',
        'location_id',
        'category_id',
        'problem_id',
        'priority',
        'status',
        'waiting_reason',
        'description',
        'solution',
        'first_response_at',
        'resolved_at',
        'due_at',
    ];

    /**
     * Conversiones de tipos.
     * Los ENUM se manejan como string; las fechas como Carbon.
     */
    protected $casts = [
        'priority' => 'string',
        'status' => 'string',
        'waiting_reason' => 'string',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    /**
     * Usuario que reportó el incidente.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Técnico asignado al ticket.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Activo afectado por el incidente.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Ubicación donde ocurrió el incidente.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Categoría del incidente.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Problema (causa raíz) asociado al ticket.
     */
    public function problem(): BelongsTo
    {
        return $this->belongsTo(Problem::class);
    }

    /**
     * Comentarios/bitácora del ticket.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    /**
     * Historial de cambios de estado.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(TicketStatusHistory::class);
    }

    /**
     * Evidencias adjuntas al ticket.
     */
    public function evidences(): HasMany
    {
        return $this->hasMany(TicketEvidence::class);
    }

    /**
     * Eventos de SLA registrados para el ticket.
     */
    public function slaEvents(): HasMany
    {
        return $this->hasMany(SlaEvent::class);
    }

    /**
     * Calcula la fecha límite de resolución según la política SLA y horario laboral.
     */
    public function calculateSlaDueDate(): ?Carbon
    {
        $policy = SlaPolicy::forPriority($this->priority);

        if ($policy === null || (float) $policy->resolution_hours <= 0) {
            return null;
        }

        $schedule = app(SlaScheduleSynchronizer::class)->schedule();
        $minutes = (int) round(((float) $policy->resolution_hours) * 60);
        $start = Carbon::parse($this->created_at ?? now());

        return app(SlaCalculator::class)->calculateDueDate($start, $minutes, $schedule);
    }
}
