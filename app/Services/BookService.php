<?php

namespace App\Services;

use App\Exceptions\BookIsAlreadyReserwedException;
use App\Exceptions\NotEnoughBookException;
use App\Jobs\ImportBooksJob;
use App\Models\Book;
use App\Models\BookCheckout;
use App\Models\BookCheckoutHistory;
use App\Models\Reserve;
use App\Models\User;

class BookService
{
    public function import($file): void
    {
        $fileName = time().'_'.$file->getClientOriginalName();
        $filepath = $file->storeAs('uploads', $fileName, 'public');
        dispatch(new ImportBooksJob($filepath))->onQueue('import');
    }

    public function reserveBook(User $user, Book $book, $reservedDate): Reserve
    {
        if ($book->getAmount() <= 0) {
            throw new NotEnoughBookException();
        }

        if ($user->reserves()->where('book_id', $book->id)->exists()) {
            throw new BookIsAlreadyReserwedException();
        }

        $reserve = Reserve::query()->firstOrCreate([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ], [
            'reserved_date' => $reservedDate
        ]);

        return $reserve;
    }

    public function checkoutBook(User $user, Book $book): BookCheckout
    {
        if ($book->getAmount() <= 0) {
            throw new NotEnoughBookException();
        }

        $bookCheckout = BookCheckout::query()->firstOrCreate([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ], [
            'borrowed_date' => now()
        ]);

        $this->saveBookCheckoutInHistory($bookCheckout);

        return $bookCheckout;
    }

    public function returnBook(BookCheckout $bookCheckout): void
    {
        $this->saveBookReturnInHistory($bookCheckout);
        $bookCheckout->delete();
    }

    public function saveBookCheckoutInHistory($bookCheckout): void
    {
        BookCheckoutHistory::query()->firstOrCreate([
            'user_id' => $bookCheckout->user_id,
            'book_id' => $bookCheckout->book_id,
            'borrowed_date' => $bookCheckout->borrowed_date
        ]);
    }

    public function saveBookReturnInHistory($bookCheckout): void
    {
        BookCheckoutHistory::query()->updateOrCreate([
            'user_id' => $bookCheckout->user_id,
            'book_id' => $bookCheckout->book_id,
            'borrowed_date' => $bookCheckout->borrowed_date
        ], [
            'returned_date' => now()
        ]);
    }
}
