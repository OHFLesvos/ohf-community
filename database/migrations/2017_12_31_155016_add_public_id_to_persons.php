<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Person;

class AddPublicIdToPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->string('public_id', 32)->after('id');
        });

        foreach (Person::withTrashed()->get() as $person) {
            $person->public_id = Person::createUUID();
            $person->save();
        }

        Schema::table('persons', function (Blueprint $table) {
            $table->unique('public_id');
        });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });
    }
}
