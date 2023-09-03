<?php

namespace App\Console\Commands;

use App\Models\Visitors\Visitor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AnonymizeVisitors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visitors:anonymize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anonymize visitor records who have not checked in more for a while.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = config('visitors.retention_days');
        $thresholdDate = now()->subDays($days);

        $visitors = Visitor::where('anonymized', false)
            ->whereDoesntHave('checkins', function ($qry) use ($thresholdDate) {
                $qry->whereDate('created_at', '>=', $thresholdDate->toDateString());
            })->get();

        $visitors->each(function (Visitor $visitor) {
            $visitor->name = sha1($visitor->name);
            if (filled($visitor->id_number)) {
                $visitor->id_number = sha1($visitor->id_number);
            }
            if ($visitor->date_of_birth !== null) {
                $visitor->date_of_birth = (new Carbon($visitor->date_of_birth))->startOfYear();
            }
            $visitor->anonymized = true;
            $visitor->save();
        });

        $message = "Anonymized {$visitors->count()} visitors who haven't been active since {$thresholdDate->toDateString()}.";
        $this->info($message);
        Log::info($message);

        return Command::SUCCESS;
    }
}
