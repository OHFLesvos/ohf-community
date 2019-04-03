<?php

namespace Modules\Library\Database\Seeders;

use Modules\Library\Entities\LibraryBook;

use Illuminate\Database\Seeder;

class LibraryBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(LibraryBook::class, 10)->create();
    }
}
