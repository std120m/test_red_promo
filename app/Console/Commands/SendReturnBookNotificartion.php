<?php

namespace App\Console\Commands;

use App\Jobs\SendMessageToUser;
use App\Jobs\SendPillReminderToUsers;
use App\Jobs\SendReturnNotificationJob;
use App\Models\BookCheckout;
use App\Models\Reception;
use App\Models\TelegramUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendReturnBookNotificartion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'return-notification:send {--user= : ID пользователя}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send notification for return book';

    protected $users;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($userId = $this->option('user')) {
            $this->users[] = $userId;
        } else {
            $this->users = User::all()->pluck('id');
        }

        $checkouts = BookCheckout::query()->isNeedToReturn()->whereIn('user_id', $this->users)->get();
        foreach ($checkouts as $bookCheckout) {
            SendReturnNotificationJob::dispatch($bookCheckout->user, $bookCheckout->book)->onQueue('mails');
        }
    }
}

