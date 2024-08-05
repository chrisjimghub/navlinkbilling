<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OwnedByAuthenticatedCustomerScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $customerId = auth()->user()->customer_id;

        if ($customerId !== null) {
            $builder->whereHas('account', function (Builder $query) use ($customerId) {
                $query->where('customer_id', $customerId);
            });
        }
    }
}
