<?php

namespace App\Http\Filters;

use App\Http\Filters\QueryFilter;
use Carbon\Carbon;

class BookFilter extends QueryFilter
{
    public function title(string $title): void
    {
        $this->builder->where('title', 'ilike', "%$title%");
    }

    public function query(string $query): void
    {
        $this->builder
            ->where('title', 'ilike', "%$query%")
            ->orWhereHas('authors', function ($authors) use ($query) {
                $authors->where('surname', 'ilike', "%$query%")
                    ->orWhere('name', 'ilike', "%$query%")
                    ->orWhere('second_name', 'ilike', "%$query%");
            });
    }

    public function authors(int ...$authorIds): void
    {
        if ($authorIds) {
            $this->builder->whereHas('authors', function ($authors) use ($authorIds) {
                $authors->whereIn('author_id', $authorIds);
            });
        }
    }

    public function after(string $query): void
    {
        if ($date = new Carbon($query)) {
            $this->builder->where('publication_date', '>=', $date);
        }
    }

    public function before(string $query): void
    {
        if ($date = new Carbon($query)) {
            $this->builder->where('publication_date', '<=', $date);
        }
    }
}
