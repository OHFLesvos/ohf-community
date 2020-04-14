<?php

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
        $books = factory(LibraryBook::class, 1050)->create();
        $books->each(function (LibraryBook $book) {
            if (mt_rand(1, 100) > 40) {
                $book->lendings()->saveMany(factory(LibraryLending::class, mt_rand(1, 5))->make());
            }
        });
    }
}
