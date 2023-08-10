<?php

namespace App\Http\Controllers;

use App\Http\Requests\BooksImportRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\BookService;

class BookController extends Controller
{
    public function __construct(
        protected BookService $bookService
    ) {
    }

    public function index()
    {
        $books = Book::all();
        return response()->json(BookResource::collection($books));
    }

    public function import(BooksImportRequest $request)
    {
        $books = $this->bookService->import($request->file);
        return response()->json(BookResource::collection($books));
    }
}
