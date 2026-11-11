<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    /**
     * Campos asignables de forma masiva.
     */
    protected $fillable = [
        'name',
        'address',
    ];

    /**
     * Ubicaciones que pertenecen a la sede.
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
