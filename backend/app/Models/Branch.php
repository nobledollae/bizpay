<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'code',
        'phone',
        'email',
        'address',
        'status',
    ];

    /**
     * A branch belongs to a business.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}