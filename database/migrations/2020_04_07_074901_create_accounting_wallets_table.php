<?php

use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingWalletsTable extends Migration
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
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        $hasTransactions = MoneyTransaction::count() > 0;
        if ($hasTransactions) {
            $wallet = Wallet::create([
                'name' => 'Default Wallet',
            ]);

            Schema::table('money_transactions', function (Blueprint $table) use ($wallet) {
                $table->foreignId('wallet_id')->after('id')->default($wallet->id);
                $table->foreign('wallet_id')->references('id')->on('accounting_wallets')->onDelete('cascade');
            });

            Schema::table('money_transactions', function (Blueprint $table) {
                $table->foreignId('wallet_id')->default(NULL)->change();
            });
        } else {
            Schema::table('money_transactions', function (Blueprint $table) {
                $table->foreignId('wallet_id')->after('id');
                $table->foreign('wallet_id')->references('id')->on('accounting_wallets')->onDelete('cascade');
            });
        }
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
}
