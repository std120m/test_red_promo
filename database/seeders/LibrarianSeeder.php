<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LibrarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $librarians = [];
        $librarians[] = User::query()->firstOrCreate([
            'email' => 'zina1965@mail.ru',
        ], [
            'name' => 'Зинаида',
            'password' => Hash::make('12345678')
        ]);

        foreach ($librarians as $librarian) {
            $librarian->assignRole(User::LIBRARIAN_ROLE);
        }
    }
}
