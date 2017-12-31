<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;
    
    protected $table = 'persons';

    protected $dates = ['deleted_at'];
    
    protected $fillable = ['name', 'family_name', 'case_no', 'nationality', 'remarks'];
    
    public static function boot()
    {
        static::creating(function ($model) {
            $model->search = self::createSearchString($model);
            $model->public_id = self::createUUID();
        });

        static::updating(function ($model) {
            $model->search = self::createSearchString($model);
        });
        
        parent::boot();
    }

    public static function createUUID() {
        return bin2hex(random_bytes(16));
    }
    
    private static function createSearchString($model) {
        return trim($model->name . ' ' . $model->family_name . ' ' . $model->case_no. ' ' . $model->medical_no. ' ' . $model->registration_no. ' ' . $model->section_card_no . ' ' . $model->temp_no);
    }
    
    // /**
    //  * Get the route key for the model.
    //  *
    //  * @return string
    //  */
    // public function getRouteKeyName()
    // {
    //     return 'public_id';
    // }

    public function transactions()
    {
        return $this->morphMany('App\Transaction', 'transactionable');
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
    
    public function dayTransactions($year, $month, $day) {
        $date = Carbon::createFromDate($year, $month, $day);
        $transactions = $this->transactions()
            ->whereDate('created_at', '>=', $date->toDateString())
            ->whereDate('created_at', '<', $date->addDay()->toDateString())
            ->select('value')
            ->get();
        $sum = collect($transactions)
            ->map(function($item){
                return $item->value;
            })
            ->sum();
        return $sum != 0 ? $sum : null;
    }
    
    public function scopeHasTransactionsToday($query) {
        return $query
            ->join('transactions', function($join){
                $join->on('persons.id', '=', 'transactions.transactionable_id')
                    ->where('transactionable_type', 'App\Person');
            })
			->groupBy('persons.id')
            ->whereDate('transactions.created_at', '=', Carbon::today()->toDateString());
    }
}
