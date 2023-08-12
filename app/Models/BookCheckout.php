<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kirschbaum\PowerJoins\PowerJoins;

class BookCheckout extends Model
{
    use HasFactory;
    use Filterable;
    use PowerJoins;

    public const MAX_DAYS_COUNT_FOR_READING = 14;

    protected $table = 'book_checkouts';
    protected $fillable = ['book_id', 'borrowed_date', 'user_id'];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ScopeIsNeedToReturn(Builder $builder)
    {
        $builder->where('borrowed_date', '<', now()->subDays(self::MAX_DAYS_COUNT_FOR_READING));
    }

}
