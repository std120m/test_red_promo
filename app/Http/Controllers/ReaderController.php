<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReaderResource;
use App\Models\User;

class ReaderController extends Controller
{    
    public function index()
    {
        $users = User::query()->role(User::READER_ROLE)->get();
        return response()->json(ReaderResource::collection($users));
    }
}
