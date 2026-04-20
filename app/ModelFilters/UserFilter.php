<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    public function searchTerm(string $searchTerm)
    {
        return $this->whereAny(['name', 'email'], 'like', "%{$searchTerm}%");
    }

    public function sortBy(string $sortBy)
    {
        $allowedFields = collect(['name', 'email', 'role']);
        if ($allowedFields->doesntContain($sortBy)) {
            return $this;
        }

        $direction = request()->query('sortDirection', 'asc');

        return $this->orderBy($sortBy, $direction);
    }

    public function role(string $role)
    {
        return $this->where('role', $role);
    }

    public function setup()
    {
        $this->excludeCurrentUser();
    }

    protected function excludeCurrentUser()
    {
        $this->whereNot('id', request()->user()->id);
    }
}
