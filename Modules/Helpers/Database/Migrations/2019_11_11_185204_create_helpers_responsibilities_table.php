<?php

use Modules\Helpers\Entities\Helper;
use Modules\Helpers\Entities\Responsibility;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelpersResponsibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helpers_responsibilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->unsignedInteger('capacity')->nullable();
            $table->boolean('available')->default(true);
            $table->timestamps();
        });
        Schema::create('helpers_helper_responsibility', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('helper_id');
            $table->unsignedBigInteger('responsibility_id');
            $table->foreign('helper_id')->references('id')->on('helpers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('responsibility_id')->references('id')->on('helpers_responsibilities')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::table('helpers')
            ->select('id', 'responsibilities')
            ->get()
            ->each(function($h) {
                if ($h->responsibilities != null && !empty($h->responsibilities)) {
                    collect(json_decode($h->responsibilities))
                        ->each(function($r) use($h) {
                            $responsibility = Responsibility::firstOrCreate(['name' => $r]);
                            $responsibility->helpers()->attach($h->id);
                        });
                }
            });
        Schema::table('helpers', function (Blueprint $table) {
            $table->dropColumn('responsibilities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $map = Helper::all()->mapWithKeys(function($helper){
            $arr = $helper->responsibilities->pluck('name')->toArray();
            return [$helper->id => count($arr) > 0 ? json_encode($arr) : null];
        });
        Schema::table('helpers', function (Blueprint $table) {
            $table->text('responsibilities')
                ->nullable()
                ->comment('Areas of responsibility the helper is involved in')
                ->after('person_id');
        });
        $map->each(function($item, $key){
            DB::table('helpers')
                ->where('id', $key)
                ->update(['responsibilities' => $item]);
        });
        Schema::dropIfExists('helpers_helper_responsibility');
        Schema::dropIfExists('helpers_responsibilities');
    }
}
