<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Transaction;
use App\CouponType;
use App\CouponHandout;
use App\Person;
use Illuminate\Support\Facades\DB;

class MigratePersonTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $drachmaCoupon = CouponType::findOrFail(1);
        $boutiqueCoupon = CouponType::findOrFail(3);
        $diapersCoupon = CouponType::findOrFail(4);

        Transaction
            ::where('transactionable_type', 'App\Person')
            ->groupBy(DB::raw('date(created_at)'), 'transactionable_id')
            ->select('id', DB::raw('date(created_at) as date'), DB::raw('sum(value) as amount'), 'transactionable_id', 'user_id')
            ->having('amount', '>', 0)
            ->get()
            ->each(function($t) use($drachmaCoupon) {
                CouponHandout::create([
                    'date' => $t->date,
                    'amount' => $t->amount,
                    'person_id' => $t->transactionable_id,
                    'coupon_type_id' => $drachmaCoupon->id,
                    'user_id' => $t->user_id,
                ]);
            });

        Transaction::where('transactionable_type', 'App\Person')->delete();

        Person::whereNotNull('boutique_coupon')
            ->select('id', DB::raw('boutique_coupon as date'))
            ->get()
            ->each(function($t) use($boutiqueCoupon) {
                CouponHandout::create([
                    'date' => $t->date,
                    'amount' => 1,
                    'person_id' => $t->id,
                    'coupon_type_id' => $boutiqueCoupon->id,
                    'user_id' => $t->user_id,
                ]);
            });

        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('boutique_coupon');
        });

        Person::whereNotNull('diapers_coupon')
            ->select('id', DB::raw('diapers_coupon as date'))
            ->get()
            ->each(function($t) use($diapersCoupon) {
                CouponHandout::create([
                    'date' => $t->date,
                    'amount' => 1,
                    'person_id' => $t->id,
                    'coupon_type_id' => $diapersCoupon->id,
                    'user_id' => $t->user_id,
                ]);
            });

        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('diapers_coupon');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CouponHandout::truncate();
        Schema::table('persons', function (Blueprint $table) {
            $table->timestamp('boutique_coupon')->nullable();
        });
        Schema::table('persons', function (Blueprint $table) {
            $table->timestamp('diapers_coupon')->nullable();
        });
    }
}
