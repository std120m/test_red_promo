<?php

namespace App\Http\Filters;

use App\Http\Filters\QueryFilter;
use Carbon\Carbon;

class ReserveFilter extends QueryFilter
{
    public function title(string $query): void
    {
        $this->builder->whereHas('book', function($books) use ($query) {
            $books->where('title', 'ilike', "%$query%");
        });
    }

    public function user(int $userId): void
    {
        $this->builder->where('user_id', $userId);
    }

    public function after(string $query): void
    {
        if ($date = new Carbon($query)) {
            $this->builder->where('reserved_date', '>=', $date);
        }
    }

    public function before(string $query): void
    {
        if ($date = new Carbon($query)) {
            $this->builder->where('reserved_date', '<=', $date);
        }
    }
}
