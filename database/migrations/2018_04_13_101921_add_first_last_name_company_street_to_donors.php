<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Donor;

class AddFirstLastNameCompanyStreetToDonors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->text('street')->nullable()->after('name');
            $table->text('company')->nullable()->after('name');
            $table->text('last_name')->nullable()->after('name');
            $table->text('first_name')->nullable()->after('name');
        });
        Donor::all()->each(function($donor){
            $name_parts = explode(' ', $donor->name);
            $first_name = isset($name_parts[0]) ? $name_parts[0] : null;
            $last_name = isset($name_parts[1]) ? $name_parts[1] : null;
            $donor->first_name = $first_name;
            $donor->last_name = $last_name;
            $donor->street = $donor->address;
            $donor->save();
        });
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->string('address')->after('id')->nullable();
            $table->string('name')->after('id');
        });
        Donor::all()->each(function($donor){
            $donor->name = $donor->first_name . ' ' . $donor->last_name;
            $donor->address = $donor->street;
            $donor->save();
        });
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('company');
            $table->dropColumn('street');
        });
    }
}
