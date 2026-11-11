<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Categorías pertenecientes al departamento.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
