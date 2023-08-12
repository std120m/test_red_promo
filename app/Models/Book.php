<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kirschbaum\PowerJoins\PowerJoins;

class Book extends Model
{
    use HasFactory;
    use Filterable;
    use PowerJoins;

    protected $table = 'books';
    protected $fillable = ['title', 'publication_date', 'amount'];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_book', 'book_id', 'author_id');
    }

    public function reserves(): HasMany
    {
        return $this->hasMany(Reserve::class, 'book_id');
    }

    public function checkouts(): HasMany
    {
        return $this->hasMany(BookCheckout::class, 'book_id');
    }

    public function getAmount(): int
    {
        return $this->amount - $this->reserves->count();
    }
}
