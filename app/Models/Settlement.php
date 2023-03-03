<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Settlement extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

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
     * Get the settlementType that owns the Settlement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function settlementType(): BelongsTo
    {
        return $this->belongsTo(SettlementType::class);
    }
}
