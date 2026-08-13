<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * Users assigned to this branch through business memberships.
 */
public function memberships(): HasMany
{
    return $this->hasMany(BusinessMembership::class);
}

    /**
     * A branch belongs to a business.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}