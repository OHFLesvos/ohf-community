<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageCodeFieldToLibraryBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('library_books', function (Blueprint $table) {
            $table->string('language_code', 2)->nullable()->after('language');
        });

        $languages = Languages::lookup()
            ->map(fn ($l) => strtolower($l))
            ->flip();

        DB::table('library_books')
            ->select('id', 'language')
            ->whereNotNull('language')
            ->get()
            ->each(function ($book) use ($languages) {
                DB::table('library_books')
                    ->where('id', $book->id)
                    ->update([
                        'language_code' => $languages->get(strtolower($book->language)),
                    ]);
            });

        Schema::table('library_books', function (Blueprint $table) {
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
        Schema::table('library_books', function (Blueprint $table) {
            $table->string('language')->nullable()->after('language_code');
        });

        $languages = Languages::lookup();

        DB::table('library_books')
            ->select('id', 'language_code')
            ->whereNotNull('language_code')
            ->get()
            ->each(function ($book) use ($languages) {
                DB::table('library_books')
                    ->where('id', $book->id)
                    ->update([
                        'language' => $languages->get($book->language_code),
                    ]);
            });

        Schema::table('library_books', function (Blueprint $table) {
            $table->dropColumn('language_code');
        });
    }
}
