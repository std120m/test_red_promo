<?php

namespace App\Http\Controllers;

use App\Exceptions\NotEnoughBookException;
use App\Http\Filters\BookCheckoutFilter;
use App\Http\Filters\ReserveFilter;
use App\Http\Requests\BookCheckoutRequest;
use App\Http\Resources\BookCheckoutResource;
use App\Models\Book;
use App\Models\BookCheckout;
use App\Models\User;
use App\Services\BookService;
use App\Services\QueryFilter\ListResponse;

class BookCheckoutController extends Controller
{
    public function __construct(
        protected BookService $bookService
    ) {
    }

    public function index(BookCheckoutFilter $filter)
    {
        [$list, $count] = (new ListResponse(BookCheckout::class, $filter))->toArray();
        return response()->json(BookCheckoutResource::collection($list))->withHeaders([
            'Access-Control-Expose-Headers' => 'X-Total-Count',
            'X-Total-Count' => $count
        ]);
    }

    public function show(int $borrowedBookId)
    {
        if (!$book = BookCheckout::find($borrowedBookId)) {
            return response()->json(['error' => 'BORROWED_BOOK_NOT_EXISTS'], 404);
        }

        return response()->json(BookCheckoutResource::make($book));
    }

    public function store(BookCheckoutRequest $request)
    {
        $user = User::find($request->user_id);
        $book = Book::find($request->book_id);

        try {
            $bookCheckout = $this->bookService->checkoutBook($user, $book);
        } catch (NotEnoughBookException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json(BookCheckoutResource::make($bookCheckout));
    }

    public function return(int $checkoutBookId)
    {
        if (!$book = BookCheckout::find($checkoutBookId)) {
            return response()->json(['error' => 'BORROWED_BOOK_NOT_EXISTS'], 404);
        }

        $this->bookService->returnBook($book);
        return response()->json();
    }
}
