<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->dropView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->createView());
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
