<?php

namespace App\Http\Controllers;

use App\Exceptions\BookIsAlreadyReserwedException;
use App\Exceptions\NotEnoughBookException;
use App\Http\Filters\ReserveFilter;
use App\Http\Requests\ReserveRequest;
use App\Http\Requests\UpdateReserveRequest;
use App\Http\Resources\ReserveResource;
use App\Models\Book;
use App\Models\Reserve;
use App\Models\User;
use App\Services\BookService;
use App\Services\QueryFilter\ListResponse;

class ReserveController extends Controller
{
    public function __construct(
        protected BookService $bookService
    ) {
    }

    public function index(ReserveFilter $filter)
    {
        [$list, $count] = (new ListResponse(Reserve::class, $filter))->toArray();
        return response()->json(ReserveResource::collection($list))->withHeaders([
            'Access-Control-Expose-Headers' => 'X-Total-Count',
            'X-Total-Count' => $count
        ]);
    }

    public function show(int $reserveId)
    {
        if (!$reserve = Reserve::find($reserveId)) {
            return response()->json(['error' => 'RESERVE_NOT_EXISTS'], 404);
        }

        return response()->json(ReserveResource::make($reserve));
    }

    public function store(ReserveRequest $request)
    {
        $user = User::find($request->user_id);
        $book = Book::find($request->book_id);

        try {
            $reserve = $this->bookService->reserveBook($user, $book, $request->reserved_date);
        } catch (NotEnoughBookException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (BookIsAlreadyReserwedException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json(ReserveResource::make($reserve));
    }

    public function update(int $reserveId, UpdateReserveRequest $request)
    {
        if (!$reserve = Reserve::find($reserveId)) {
            return response()->json(['error' => 'RESERVE_NOT_EXISTS'], 404);
        }

        $reserve->update(['reserved_date' => $request->reserved_date]);
        return response()->json(ReserveResource::make($reserve));
    }

    public function destroy(int $reserveId)
    {
        if (!$reserve = Reserve::find($reserveId)) {
            return response()->json(['error' => 'RESERVE_NOT_EXISTS'], 404);
        }

        $reserve->delete();
        return response()->json();
    }

}
