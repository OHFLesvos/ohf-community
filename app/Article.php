<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Article extends Model {
    use CascadesDeletes;

    protected $cascadeDeletes = ['transactions'];

    public function project() {
        return $this->belongsTo('App\Project');
    }

    public function transactions() {
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
