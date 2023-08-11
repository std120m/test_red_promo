<?php

namespace App\Services\QueryFilter;


use App\Http\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

class ListResponse
{
    private Builder $builder;
    private QueryFilter $filter;

    public function __construct(string $modelName, QueryFilter $filter)
    {
        $this->filter = $filter;
        $this->builder = $modelName::filter($filter);
    }

    public function toArray()
    {
        return [
            $this->getList(),
            $this->getCount()
        ];
    }

    public function getList()
    {
        return clone $this->builder->clone()->ofPage($this->filter)->get();
    }

    public function getCount()
    {
        return $this->builder->clone()->count();
    }
}
