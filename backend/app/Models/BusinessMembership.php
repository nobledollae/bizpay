<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id',
        'role_id',
        'branch_id',
        'status',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /**
     * The user associated with this membership.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The business associated with this membership.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * The role assigned through this membership.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * The branch associated with this membership.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}