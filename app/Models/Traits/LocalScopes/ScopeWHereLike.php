<?php

namespace App\Models\Traits\LocalScopes;

trait ScopeWHereLike
{
    public function scopeWhereLike($query, $column, $value)
    {
        return $query->where($column, 'like', '%' . $value . '%');
    }
}
