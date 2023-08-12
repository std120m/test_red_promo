<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\BookCheckoutHistory;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;

class GetLibraryReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:make  {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected string $file;
    protected string $link;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument("userId");
        if (!$user = User::query()->role(User::LIBRARIAN_ROLE)->firstWhere('id', $userId))
            return;

        $this->createCsv($user);

        if ($userId && $user) {
            $this->sendCsvFile($user->email);
        }
        return;
    }

    public function createCsv($user)
    {
        $history = BookCheckoutHistory::query()->orderBy('borrowed_date')->get();
        $books = Book::all();
        $this->file = storage_path('app/public') . "/report.csv";
        $this->link = config('app.url')."/storage/report.csv";

        (new FastExcel($books))->configureCsv(';', '"', '\n', true)
            ->export($this->file, function ($line) {
                return [
                    'Название книги' => $line->title,
                    'Количество взятий' => $line->checkouts()->count(),
                    'Cрок использования' => $line->checkouts()
                ];
            });
    }

    public function sendCsvFile(string $email)
    {
        Mail::to($email)->queue((new ConflictedGrainsExport($this->link))->onQueue('emails'));
    }
}
