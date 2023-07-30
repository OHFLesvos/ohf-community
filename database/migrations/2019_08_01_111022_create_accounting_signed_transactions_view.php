<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        // workaround for laravels limitation to change tables with an enum
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->decimal('amount', 8, 2)->unsigned()->change();
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
        DB::statement($this->dropView());
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
SELECT date, -amount as amount, receipt_no, category, project, description, remarks from money_transactions where type = 'spending'
union all
SELECT date, amount, receipt_no, category, project, description, remarks from money_transactions where type = 'income'
SQL;
    }
};
