<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ReaderService
{
    public function createReader(string $email, string $name, string $password) : User
    {        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $user->assignRole(User::READER_ROLE);

        return $user;
    }
}
