<?php

namespace Modules\Bank\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Bank\Entities\CouponHandout;

use OwenIt\Auditing\Models\Audit;

class WithdrawalController extends Controller
{
    /**
     * View for showing coupon transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function transactions() {
		return view('bank::withdrawal.transactions', [
            'transactions' => Audit::where('auditable_type', CouponHandout::class)
                ->orderBy('created_at', 'DESC')
                ->paginate(100),
		]);
    }

}
