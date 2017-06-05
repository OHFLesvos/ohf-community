<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Person extends Model
{
    protected $table = 'persons';

    public static function boot()
    {
        static::creating(function ($model) {
            $model->search = self::createSearchString($model);
        });

        static::updating(function ($model) {
            $model->search = self::createSearchString($model);
        });
        
        parent::boot();
    }
    
    private static function createSearchString($model) {
        return $model->name . ' ' . $model->family_name . ' ' . $model->case_no;
    }
    
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
    
    public function todaysTransaction() {
        $transactions = $this->transactions()
            ->whereDate('created_at', '=', Carbon::today()->toDateString())
            ->select('value')
            ->get();
        return collect($transactions)
            ->map(function($item){
                return $item->value;
            })
            ->sum();
    }

    public function yesterdaysTransaction() {
        $transactions = $this->transactions()
            ->whereDate('created_at', '=', Carbon::yesterday()->toDateString())
            ->select('value')
            ->get();
        return collect($transactions)
            ->map(function($item){
                return $item->value;
            })
            ->sum();
    }
}
