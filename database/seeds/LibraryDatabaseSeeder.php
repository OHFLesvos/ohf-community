<?php

use App\Models\Library\LibraryBook;

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
        factory(LibraryBook::class, 500)->create();
    }
}
