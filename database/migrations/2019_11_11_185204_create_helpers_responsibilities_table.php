<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            ->each(function ($helper) {
                if ($helper->responsibilities != null && ! empty($helper->responsibilities)) {
                    collect(json_decode($helper->responsibilities))
                        ->each(function ($responsibilityName) use ($helper) {
                            DB::table('helpers_responsibilities')
                                ->updateOrInsert(['name' => $responsibilityName]);
                            $responsibilityId = DB::table('helpers_responsibilities')
                                ->where('name', $responsibilityName)
                                ->value('id');
                            DB::table('helpers_helper_responsibility')
                                ->insert([
                                    'helper_id' => $helper->id,
                                    'responsibility_id' => $responsibilityId,
                                ]);
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
        Schema::table('helpers_helper_responsibility', function (Blueprint $table) {
            $table->timestamps();
        });

        $map = DB::table('helpers')->get()
            ->mapWithKeys(function ($helper) {
                $arr = DB::table('helpers_responsibilities')
                    ->select('name')
                    ->join('helpers_helper_responsibility', 'helpers_responsibilities.id', '=', 'helpers_helper_responsibility.responsibility_id')
                    ->where('helper_id', $helper->id)
                    ->get()
                    ->pluck('name')
                    ->toArray();
                return [$helper->id => count($arr) > 0 ? json_encode($arr) : null];
            });
        Schema::table('helpers_helper_responsibility', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('helpers', function (Blueprint $table) {
            $table->text('responsibilities')
                ->nullable()
                ->comment('Areas of responsibility the helper is involved in')
                ->after('person_id');
        });
        $map->each(function ($item, $key) {
            DB::table('helpers')
                ->where('id', $key)
                ->update(['responsibilities' => $item]);
        });
        Schema::dropIfExists('helpers_helper_responsibility');
        Schema::dropIfExists('helpers_responsibilities');
    }
};
