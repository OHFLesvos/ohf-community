<?php

use Illuminate\Database\Seeder;
use App\LibraryBook;

class LibraryBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
            [
                'title' => 'Another Country',
                'author' => 'James Baldwin',
                'isbn' => '9780140184112',
            ],
        ] as $book) {
            LibraryBook::create($book);
        }
    }
}
