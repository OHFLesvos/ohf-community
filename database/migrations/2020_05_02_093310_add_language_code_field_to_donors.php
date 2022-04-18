<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('donors', function (Blueprint $table) {
            $table->string('language_code', 2)->nullable()->after('language');
        });

        $languages = Languages::lookup()
            ->map(fn ($l) => strtolower($l))
            ->flip();

        DB::table('donors')
            ->select('id', 'language')
            ->whereNotNull('language')
            ->get()
            ->each(function ($donor) use ($languages) {
                DB::table('donors')
                    ->where('id', $donor->id)
                    ->update([
                        'language_code' => $languages->get(strtolower($donor->language), $donor->language),
                    ]);
            });

        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn('language');
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
            $table->string('language')->nullable()->after('language_code');
        });

        $languages = Languages::lookup();

        DB::table('donors')
            ->select('id', 'language_code')
            ->whereNotNull('language_code')
            ->get()
            ->each(function ($donor) use ($languages) {
                DB::table('donors')
                    ->where('id', $donor->id)
                    ->update([
                        'language' => $languages->get($donor->language_code),
                    ]);
            });

        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn('language_code');
        });
    }
};
