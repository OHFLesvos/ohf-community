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
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->decimal('fees', 8, 2)->nullable()->after('amount');
        });

        DB::statement($this->dropView());
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->dropColumn('fees');
        });
    }

    private function dropView(): string
    {
        return <<<'SQL'
DROP VIEW IF EXISTS `accounting_signed_transactions`;
SQL;
    }

    private function createView(): string
    {
        return <<<'SQL'
CREATE VIEW `accounting_signed_transactions` AS
SELECT wallet_id, date, -amount as amount, fees, receipt_no, category, project, description, remarks from money_transactions where type = 'spending'
union all
SELECT wallet_id, date, amount, fees, receipt_no, category, project, description, remarks from money_transactions where type = 'income'
SQL;
    }
};
