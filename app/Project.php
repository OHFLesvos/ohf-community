<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Project extends Model
{
    public function articles() {
        return $this->hasMany('App\Article');
    }

    public function transactions()
    {
        return $this->morphMany('App\Transaction', 'transactionable');
    }

    public function dayTransactions($date) {
        $transactions = $this->transactions()
            ->whereDate('created_at', '>=', $date->toDateString())
            ->whereDate('created_at', '<', (clone $date)->addDay()->toDateString())
            ->select('value')
            ->get();
        $sum = collect($transactions)
            ->map(function($item){
                return $item->value;
            })
            ->sum();
        return $sum != 0 ? $sum : null;
    }

    public function avgNumTransactions() {
        // TODO: Date from/to selection
        $avg = $this->transactions()
            ->select(DB::raw('AVG(value) as value'))
            ->get()
            ->first()
            ->value;
        return $avg != null ? round($avg, 1) : null;
    }

    public function maxNumTransactions() {
        // TODO Date from/to selection
        return $this->transactions()
            ->select(DB::raw('MAX(value) as value'))
            ->get()
            ->first()
            ->value;
    }

    public function weekTransactions($date) {
        $transactions = $this->transactions()
            ->whereDate('created_at', '>=', (clone $date)->startOfWeek()->toDateString())
            ->whereDate('created_at', '<', (clone $date)->endOfWeek()->toDateString())
            ->select('value')
            ->get();
        $sum = collect($transactions)
            ->map(function($item){
                return $item->value;
            })
            ->sum();
        return $sum != 0 ? $sum : null;
    }

    public function monthTransactions($date) {
        $transactions = $this->transactions()
            ->whereDate('created_at', '>=', (clone $date)->startOfMonth()->toDateString())
            ->whereDate('created_at', '<', (clone $date)->endOfMonth()->toDateString())
            ->select('value')
            ->get();
        $sum = collect($transactions)
            ->map(function($item){
                return $item->value;
            })
            ->sum();
        return $sum != 0 ? $sum : null;
    }

}
