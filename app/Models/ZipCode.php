<?php

namespace App\Models;

use App\Models\Traits\CleanString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZipCode extends Model
{
    use HasFactory, SoftDeletes, CleanString;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'zip_code',
        'locality',
        'federal_entity_id',
        'municipality_id',
    ];

    /**
     * attributes to clean
     *
     * @return array
     */
    public function cleanable() : array
    {
        return [
            'locality'
        ];
    }

    /**
     * Get the federalEntity that owns the ZipCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function federalEntity(): BelongsTo
    {
        return $this->belongsTo(FederalEntity::class);
    }

    /**
     * Get the municipality that owns the ZipCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    /**
     * Get all of the settlements for the ZipCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settlements(): HasMany
    {
        return $this->hasMany(Settlement::class);
    }

    /**
     * Key for route binding
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'zip_code';
    }
}
