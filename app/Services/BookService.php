<?php

namespace App\Services;

use App\Jobs\ImportBooks;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BookService
{
    public function import($file)
    {
        $fileName = time().'_'.$file->getClientOriginalName();
        $filepath = $file->storeAs('uploads', $fileName, 'public');
        dispatch(new ImportBooks($filepath))->onQueue('import');
        return response();
    }
}
