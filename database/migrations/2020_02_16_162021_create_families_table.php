<?php

use App\Models\People\Family;
use App\Models\People\Person;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
        Schema::table('persons', function (Blueprint $table) {
            $table->bigInteger('family_id')->nullable()->unsigned()->after('public_id');
            $table->foreign('family_id')->references('id')->on('families')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('persons', function (Blueprint $table) {
            $table->dropForeign(['mother_id']);
            $table->dropForeign(['father_id']);
            $table->dropForeign(['partner_id']);
            $table->dropColumn(['mother_id', 'father_id', 'partner_id']);
        });

        Person::whereNotNull('police_no')
            ->doesntHave('family')
            ->groupBy('police_no')
            ->select('police_no')
            ->havingRaw ('COUNT(*) > 1')
            ->get()
            ->pluck('police_no')
            ->each(function ($p) {
                $family = new Family();
                $family->save();
                $family->members()->saveMany(Person::where('police_no', $p)->get());
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
            $table->integer('mother_id')->nullable()->unsigned();
            $table->integer('father_id')->nullable()->unsigned();
            $table->foreign('mother_id')->references('id')->on('persons')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('father_id')->references('id')->on('persons')->onDelete('set null')->onUpdate('cascade');
            $table->integer('partner_id')->nullable()->unsigned();
            $table->foreign('partner_id')->references('id')->on('persons')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('persons', function (Blueprint $table) {
            $table->dropForeign(['family_id']);
            $table->dropColumn('family_id');
        });
        Schema::dropIfExists('families');
    }
}
