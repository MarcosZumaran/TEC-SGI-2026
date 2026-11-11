<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'name',
        'department_id',
    ];

    /**
     * Departamento al que pertenece la categoría.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Tickets que pertenecen a esta categoría.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Artículos de la base de conocimientos de esta categoría.
     */
    public function knowledgeBase(): HasMany
    {
        return $this->hasMany(KnowledgeBase::class);
    }
}
