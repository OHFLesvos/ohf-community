<?php

namespace Database\Seeders;

use App\Models\Library\LibraryBook;
use App\Models\Library\LibraryLending;
use Illuminate\Database\Seeder;

class LibraryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LibraryBook::factory()
            ->count(1050)
            ->create()
            ->each(function (LibraryBook $book) {
            if (mt_rand(1, 100) > 40) {
                $book->lendings()->saveMany(LibraryLending::factory()
                    ->count(mt_rand(1, 5))
                    ->make());
            }
        });
    }
}
