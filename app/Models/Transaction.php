<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ScopedBy([UserScope::class])]
class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'to_user_id',
        'currency',
        'status'
    ];

    public function toUser(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'to_user_id');
    }
}
