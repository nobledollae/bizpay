<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'scope',
        'status',
    ];

/**
 * Business memberships using this role.
 */
public function memberships(): HasMany
{
    return $this->hasMany(BusinessMembership::class);
}
    /**
     * A role can have many permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions'
        )->withTimestamps();
    }
}