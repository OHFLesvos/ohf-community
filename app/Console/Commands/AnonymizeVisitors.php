<?php

namespace App\Console\Commands;

use App\Models\Visitors\Visitor;
use Illuminate\Console\Command;

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
    protected $description = 'Anonymize visitor records who have not checked in more for more than 60 days.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $visitors = Visitor::where('anonymized', false)
            ->whereDoesntHave('checkins', function ($qry) {
                $qry->whereDate('created_at', '>=', now()->subDays(60)->toDateString());
            })->get();

        $visitors->each(function (Visitor $visitor) {
            $visitor->name = sha1($visitor->name);
            $visitor->id_number = sha1($visitor->id_number);
            if ($visitor->date_of_birth !== null) {
                $visitor->date_of_birth = $visitor->date_of_birth->startOfYear();
            }
            $visitor->anonymized = true;
            $visitor->save();
        });

        $this->info("Anonymized {$visitors->count()} visitors.");

        return Command::SUCCESS;
    }
}
