<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
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
}
