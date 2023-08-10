<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ImportBooksFromCsv extends Command
{
    const BOOK_TITLE_COLUMN = 0;
    const AUTHORS_COLUMN = 1;
    const PUBLICATION_DATE_COLUMN = 2;
    const AMOUNT_COLUMN = 3;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:import-from-csv {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import books from csv';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('filepath');
        $csvFile = file($filePath);

        $skipHeader = true;
        $count = 0;
        foreach ($csvFile as $row) {
            if($skipHeader) {
                $skipHeader = false;
                continue;
            }

            $count++;
            $cells = str_getcsv($row, ";");

            if (count($cells) != 4)
                continue;

            $book = $this->createBook($cells);
            $authors = $this->createAuthors($cells[self::AUTHORS_COLUMN]);
            $book->authors()->sync($authors->pluck('id'));
        }
    }

    protected function createBook(array $cells): Book
    {
        $book = Book::query()->firstOrCreate([
            'title' => $cells[self::BOOK_TITLE_COLUMN],
            'publication_date' => $cells[self::PUBLICATION_DATE_COLUMN]
        ]);
        $book->update(['amount' => $book->amount + $cells[self::AMOUNT_COLUMN]]);

        return $book;
    }

    protected function createAuthors(string $authorsCell): Collection
    {
        $authors = collect();
        foreach (explode(',', trim($authorsCell)) as $authorData) {
            $fio = explode(' ', $authorData);
            $authors->push(Author::query()->firstOrCreate([
                'surname' => Arr::get($fio, 0),
                'name' => Arr::get($fio, 1),
                'second_name' => Arr::get($fio, 2)
            ]));
        }

        return $authors;
    }
}
