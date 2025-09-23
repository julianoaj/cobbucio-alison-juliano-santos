<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UserScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $table = $model->getTable();
        $builder->where(function (Builder $query) use ($model, $table) {
            $query->where('user_id', auth()->user()->id ?? $model->user_id)
            ->orWhere('to_user_id', auth()->user()->id ?? $model->to_user_id);
        });
    }
}
