<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use App\Services\ReaderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected ReaderService $readerService) {
    }
   
    public function singUp(SignUpRequest $request)
    {
        $user = $this->readerService->createReader($request->email, $request->name, $request->password);
        $token = $user->createToken('token')->plainTextToken;
        return response()->json(['token' => $token])->cookie($this->createCookie($token));
    }

    public function singIn(SignInRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        /** @var User */
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

        return response()->json(['token' => $token])->cookie($this->createCookie($token));
    }

    public function logout(Request $request)
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logout'])->cookie($this->deleteCookie());
    }

    private function createCookie($token)
    {
        return cookie(
            'accessToken',
            $token,
            60 * 24,
            '/',
            null,
            false,
            false
        );
    }

    private function deleteCookie()
    {
        return cookie(
            'accessToken',
            null,
            -1,
            '/',
            null,
            false,
            false
        );
    }
}
