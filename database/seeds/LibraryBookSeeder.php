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
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'isbn' => null,
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => null,
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => null,
            ],
            [
                'title' => 'Adventures of Huckleberry Finn',
                'author' => 'Mark Twain',
                'isbn' => null,
            ],
            [
                'title' => 'Moby-Dick',
                'author' => 'Herman Melville',
                'isbn' => null,
            ],
        ] as $book) {
            LibraryBook::create($book);
        }
    }
}
