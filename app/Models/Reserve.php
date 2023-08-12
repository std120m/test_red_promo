<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kirschbaum\PowerJoins\PowerJoins;

class Reserve extends Model
{
    use HasFactory;
    use Filterable;
    use PowerJoins;

    public const MAX_RESERVATION_DAYS = 3;

    protected $table = 'reserves';
    protected $fillable = ['book_id', 'reserved_date', 'user_id'];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
