<?php

use App\Models\Accounting\Wallet;
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
        Schema::create('accounting_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        $hasTransactions = DB::table('money_transactions')->count() > 0;
        if ($hasTransactions) {
            $wallet = Wallet::create([
                'name' => 'Default Wallet',
            ]);

            Schema::table('money_transactions', function (Blueprint $table) use ($wallet) {
                $table->foreignId('wallet_id')->after('id')->default($wallet->id);
                $table->foreign('wallet_id')->references('id')->on('accounting_wallets')->onDelete('cascade');
            });
        } else {
            Schema::table('money_transactions', function (Blueprint $table) {
                $table->foreignId('wallet_id')->after('id')->default(0);
                $table->foreign('wallet_id')->references('id')->on('accounting_wallets')->onDelete('cascade');
            });
        }
        Schema::table('money_transactions', function (Blueprint $table) {
            $table->foreignId('wallet_id')->default(null)->change();
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
            $table->dropForeign(['wallet_id']);
            $table->dropColumn('wallet_id');
        });

        Schema::dropIfExists('accounting_wallets');
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
SELECT wallet_id, date, -amount as amount, receipt_no, category, project, description, remarks from money_transactions where type = 'spending'
union all
SELECT wallet_id, date, amount, receipt_no, category, project, description, remarks from money_transactions where type = 'income'
SQL;
    }
};
