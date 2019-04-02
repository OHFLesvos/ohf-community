<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\CouponReturn;

class Project extends Model
{
    public function couponReturns() {
        return $this->hasMany('App\CouponReturn');
    }

    public function dayTransactions($date) {
        $sum = $this->couponReturns()
            ->whereDate('date', $date->toDateString())
            ->select(DB::raw('SUM(amount) as sum'))
            ->get()
            ->pluck('sum')
            ->first();
        return $sum != 0 ? $sum : null;
    }

    public function avgNumTransactions() {
        // TODO: Date from/to selection
        $avg = $this->couponReturns()
            ->select(DB::raw('AVG(amount) as avg'))
            ->get()
            ->pluck('avg')
            ->first();
        return $avg != null ? round($avg, 1) : null;
    }

    public function maxNumTransactions() {
        // TODO Date from/to selection
        return $this->couponReturns()
            ->select(DB::raw('MAX(amount) as max'))
            ->get()
            ->pluck('max')
            ->first();
    }

    public function weekTransactions($date) {
        $sum = $this->couponReturns()
            ->whereDate('date', '>=', (clone $date)->startOfWeek()->toDateString())
            ->whereDate('date', '<=', (clone $date)->endOfWeek()->toDateString())
            ->select(DB::raw('SUM(amount) as sum'))
            ->get()
            ->pluck('sum')
            ->first();
        return $sum != 0 ? $sum : null;
    }

    public function monthTransactions($date) {
        $sum = $this->couponReturns()
            ->whereDate('date', '>=', (clone $date)->startOfMonth()->toDateString())
            ->whereDate('date', '<=', (clone $date)->endOfMonth()->toDateString())
            ->select(DB::raw('SUM(amount) as sum'))
            ->get()
            ->pluck('sum')
            ->first();
        return $sum != 0 ? $sum : null;
    }

}
