<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->enum('type', ['income', 'spending']);
            $table->decimal('amount', 8, 2);
            $table->unsignedInteger('receipt_no')->nullable();
            $table->string('beneficiary');
            $table->string('project');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('money_transactions');
    }
}
