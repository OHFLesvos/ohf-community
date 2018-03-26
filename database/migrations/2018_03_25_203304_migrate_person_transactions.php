<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Transaction;
use App\CouponReturn;
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
        $drachmaCoupon = CouponType::firstOrCreate([
            'name' => 'Drachma',
            'icon' => 'money',
            'daily_amount' => 2,
            'retention_period' => 1,
            'min_age' => 12,
            'order' => 0,
        ]);
        $boutiqueCoupon = CouponType::firstOrCreate([
            'name' => 'Boutique Coupon',
            'icon' => 'shopping-bag',
            'daily_amount' => 1,
            'retention_period' => 12,
            'min_age' => 15,
            'order' => 2,
        ]);
        $diapersCoupon = CouponType::firstOrCreate([
            'name' => 'Diapers Coupon',
            'icon' => 'child',
            'daily_amount' => 1,
            'retention_period' => 1,
            'max_age' => 4,
            'order' => 3,
        ]);

        DB::table('transactions')
            ->where('transactionable_type', 'App\Person')
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

        DB::table('persons')
            ->whereNotNull('boutique_coupon')
            ->select('id', DB::raw('boutique_coupon as date'))
            ->get()
            ->each(function($t) use($boutiqueCoupon) {
                CouponHandout::create([
                    'date' => $t->date,
                    'amount' => 1,
                    'person_id' => $t->id,
                    'coupon_type_id' => $boutiqueCoupon->id,
                ]);
            });

        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('boutique_coupon');
        });

        DB::table('persons')
            ->whereNotNull('diapers_coupon')
            ->select('id', DB::raw('diapers_coupon as date'))
            ->get()
            ->each(function($t) use($diapersCoupon) {
                CouponHandout::create([
                    'date' => $t->date,
                    'amount' => 1,
                    'person_id' => $t->id,
                    'coupon_type_id' => $diapersCoupon->id,
                ]);
            });

        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('diapers_coupon');
        });

        DB::table('transactions')
            ->where('transactionable_type', 'App\Project')
            ->groupBy(DB::raw('date(created_at)'), 'transactionable_id')
            ->select('id', DB::raw('date(created_at) as date'), DB::raw('sum(value) as amount'), 'transactionable_id', 'user_id')
            ->having('amount', '>', 0)
            ->get()
            ->each(function($t) use($drachmaCoupon) {
                CouponReturn::create([
                    'date' => $t->date,
                    'amount' => $t->amount,
                    'project_id' => $t->transactionable_id,
                    'coupon_type_id' => $drachmaCoupon->id,
                    'user_id' => $t->user_id,
                ]);
            });

        Transaction::where('transactionable_type', 'App\Project')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->timestamp('boutique_coupon')->nullable();
        });
        Schema::table('persons', function (Blueprint $table) {
            $table->timestamp('diapers_coupon')->nullable();
        });
        CouponHandout::truncate();
        CouponReturn::truncate();
    }
}
