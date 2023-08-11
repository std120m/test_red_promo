<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $librarian = Role::query()->firstOrCreate(['name' => User::LIBRARIAN_ROLE]);
        $reader = Role::query()->firstOrCreate(['name' => User::READER_ROLE]);

        $librarianPermissions = [
            Permission::query()->firstOrCreate(['name' => Permission::IMPORT_BOOKS_PERMISSION]),
            Permission::query()->firstOrCreate(['name' => Permission::RESERVE_BOOKS_PERMISSION]),
            Permission::query()->firstOrCreate(['name' => Permission::EDIT_RESERVE_PERMISSION]),
            Permission::query()->firstOrCreate(['name' => Permission::CHECKOUT_BOOKS_PERMISSION]),            
        ];

        $userPermissions = [
            Permission::query()->firstOrCreate(['name' => Permission::RESERVE_BOOKS_PERMISSION]),
        ];

        foreach ($librarianPermissions as $permission) {
            $librarian->givePermissionTo($permission);
        }

        foreach ($userPermissions as $permission) {
            $reader->givePermissionTo($permission);
        }
    }
}
