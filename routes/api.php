<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookCheckoutController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\ReserveController;
use App\Models\Book;
use App\Models\BookCheckout;
use App\Models\Permission;
use App\Models\Reserve;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'singUp']);
Route::post('/login', [AuthController::class, 'singIn']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::model('book', Book::class);
    Route::apiResource('/books', BookController::class)->only(['index', 'show']);
    Route::post('books/import', [BookController::class, 'import'])->middleware('can' . Permission::IMPORT_BOOKS_PERMISSION);

    Route::model('reserve', Reserve::class);
    Route::apiResource('/reserves', ReserveController::class)->only(['update'])->middleware('can' . Permission::EDIT_RESERVE_PERMISSION);
    Route::apiResource('/reserves', ReserveController::class)->only(['store'])->middleware('can' . Permission::RESERVE_BOOKS_PERMISSION);
    Route::apiResource('/reserves', ReserveController::class)->except(['store', 'update']);

    Route::group(['middleware' => ['role:' . User::LIBRARIAN_ROLE]], function () {
        Route::model('bookCheckout', BookCheckout::class);
        Route::apiResource('/book-checkouts', BookCheckoutController::class)->only(['index', 'show', 'store']);
        Route::put('/book-checkouts/{id}/return', [BookCheckoutController::class, 'return']);
        
        Route::get('/readers', [ReaderController::class, 'index']);
    });
});

