<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnowledgeBase extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nombre real de la tabla (singular, según el ER).
     */
    protected $table = 'knowledge_base';

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'category_id',
        'type',
        'title',
        'content',
        'is_published',
        'created_by',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'type' => 'string',
        'is_published' => 'boolean',
    ];

    /**
     * Categoría del artículo.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Usuario que creó el artículo.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
