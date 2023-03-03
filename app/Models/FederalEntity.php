<?php

namespace App\Models;

use App\Models\Traits\CleanString;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FederalEntity extends Model
{
    use HasFactory, HasUuids, SoftDeletes, CleanString;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'key',
        'code',
    ];

    /**
     * attributes to clean
     *
     * @return array
     */
    public function cleanable() : array
    {
        return [
            'name'
        ];
    }

}
