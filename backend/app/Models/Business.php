<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'business_type',
        'email',
        'phone',
        'address',
        'status',
    ];

    /**
     * A business can have many branches.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Users who belong to this business.
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(BusinessMembership::class);
    }
}