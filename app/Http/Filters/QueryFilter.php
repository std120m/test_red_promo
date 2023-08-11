<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->fields() as $field => $value) {
            $method = Str::camel($field);
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], (array)$value);
            }
        }

        if ($this->shouldSort()) {
            $sort = $this->request->input('_sort');
            $order = $this->request->input('_order');
            $tableName = $this->builder->getModel()->getTable();

            if (Schema::hasColumn($tableName, $sort)) {
                $this->builder->orderBy($sort, $order);
            } else {
                $tokens = explode('.', $sort);
                $sortField = array_pop($tokens);
                $relations = implode('.', $tokens);

                $relationModel = $this->builder->getModel();
                foreach ($tokens as $relationName) {
                    $relationModel = $relationModel->$relationName()->getRelated();
                }

                $relationTable = $relationModel->getTable();

                $this->builder->leftJoinRelationship($relations)
                    ->groupBy("$tableName.id")
                    ->orderByRaw("array_agg($relationTable.$sortField) $order");
            }
        }
    }

    /**
     * @param Builder $builder
     */
    public function paginate(Builder $builder)
    {
        $this->builder = $builder;

        if ($this->shouldPaginate()) {
            $start = $this->request->input('_start');
            $end = $this->request->input('_end');
            $this->builder->skip($start)->take($end - $start);
        }
    }

    public function shouldPaginate()
    {
        return $this->request->has('_start')
            && $this->request->has('_end')
            && ctype_digit($this->request->input('_start'))
            && ctype_digit($this->request->input('_end'));
    }

    public function shouldSort()
    {
        return $this->request->has('_sort');
    }

    public function id(int ...$ids)
    {
        $this->builder->whereIn($this->builder->getModel()->getTable() . '.id', $ids);
    }

    /**
     * @return array
     */
    protected function fields(): array
    {
        return array_filter(
            array_map(
                function ($value) {
                    return is_string($value) ? trim($value) : $value;
                },
                $this->request->all()
            ), fn($value) => $value !== null
        );
    }
}
