<?php

use App\Models\Accounting\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiptPicturesToMoneyTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->text('receipt_pictures')->nullable()->after('receipt_picture');
        });
        DB::table('money_transactions')
            ->whereNotNull('receipt_picture')
            ->get()
            ->each(function ($t) {
                if (! empty($t->receipt_picture)) {
                    $t->receipt_pictures = [ $t->receipt_picture ];
                    DB::table('money_transactions')
                        ->where('id', $t->id)
                        ->update(['receipt_picture' => serialize([ $t->receipt_picture ]) ]);
                }
            });
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->dropColumn('receipt_picture');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->string('receipt_picture')->nullable()->after('receipt_no');
        });
        DB::table('money_transactions')
            ->whereNotNull('receipt_pictures')
            ->get()
            ->each(function ($t) {
                if (! empty($t->receipt_pictures)) {
                    // Warning: potential data loss
                    DB::table('money_transactions')
                        ->where('id', $t->id)
                        ->update(['receipt_picture' => $t->receipt_pictures[0]]);
                }
            });
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->dropColumn('receipt_pictures');
        });
    }
}
