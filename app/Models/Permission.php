<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    public const IMPORT_BOOKS_PERMISSION = 'import_books';
   
    public const RESERVE_BOOKS_PERMISSION = 'reserve_books';
    public const EDIT_RESERVE_PERMISSION = 'edit_reserve';

    public const CHECKOUT_BOOKS_PERMISSION = 'checkout_book';
}
