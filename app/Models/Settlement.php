<?php

namespace App\Models;

use App\Models\Traits\CleanString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Settlement extends Model
{
    use HasFactory, SoftDeletes, CleanString;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'key',
        'zone_type',
        'settlement_type_id',
        'zip_code_id'
    ];

    /**
     * attributes to clean
     *
     * @return array
     */
    public function cleanable() : array
    {
        return [
            'name',
            'zone_type'
        ];
    }



    /**
     * Get the settlementType that owns the Settlement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function settlementType(): BelongsTo
    {
        return $this->belongsTo(SettlementType::class);
    }
}
