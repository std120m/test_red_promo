<?php

namespace App\Http\Controllers;

use App\Http\Filters\BookFilter;
use App\Http\Requests\BooksImportRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\ExtraBookResource;
use App\Models\Book;
use App\Models\User;
use App\Services\BookService;
use App\Services\QueryFilter\ListResponse;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function __construct(
        protected BookService $bookService
    ) {
    }

    public function index(BookFilter $filter)
    {
        [$list, $count] = (new ListResponse(Book::class, $filter))->toArray();
        return response()->json(BookResource::collection($list))->withHeaders([
            'Access-Control-Expose-Headers' => 'X-Total-Count',
            'X-Total-Count' => $count
        ]);
    }

    public function show(Book $book)
    {
        /** @var User */
        $user = Auth::user();
        if ($user->hasRole(User::LIBRARIAN_ROLE)) {
            return response()->json(ExtraBookResource::make($book));
        }

        return response()->json(BookResource::make($book));
    }

    public function import(BooksImportRequest $request)
    {
        $this->bookService->import($request->file);
        return response()->json();
    }
}
