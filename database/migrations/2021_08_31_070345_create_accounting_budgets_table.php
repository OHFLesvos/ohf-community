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
        Schema::create('accounting_budgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')
                ->nullable();
            $table->decimal('amount', 8, 2);
            $table->unsignedInteger('donor_id');
            $table->foreign('donor_id')
                ->references('id')
                ->on('donors');
            $table->timestamps();
        });

        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->foreignId('budget_id')
                ->nullable()
                ->constrained('accounting_budgets')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->dropForeign(['budget_id']);
            $table->dropColumn('budget_id');
        });

        Schema::dropIfExists('accounting_budgets');
    }
};
