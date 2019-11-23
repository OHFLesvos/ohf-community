<?php

use Modules\Accounting\Entities\MoneyTransaction;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
        MoneyTransaction::whereNotNull('receipt_picture')
            ->get()
            ->each(function($t){
                if (!empty($t->receipt_picture)) {
                    $t->receipt_pictures = [ $t->receipt_picture ];
                    $t->save();
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
        MoneyTransaction::whereNotNull('receipt_pictures')
            ->get()
            ->each(function($t){
                if (!empty($t->receipt_pictures)) {
                    // Warning: potential data loss
                    $t->receipt_picture = $t->receipt_pictures[0];
                    $t->save();
                }
            });
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->dropColumn('receipt_pictures');
        });
    }
}
