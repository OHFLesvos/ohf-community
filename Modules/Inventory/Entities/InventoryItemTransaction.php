<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Iatstuti\Database\Support\NullableFields;

class InventoryItemTransaction extends Model
{
    use NullableFields;

    public static function boot()
    {
        static::creating(function ($model) {
            $model->user_id = Auth::id();
            $model->user_name = optional(Auth::user())->name;
        });

        parent::boot();
    }

    protected $nullable = [
        'origin',
        'destination',
        'sponsor',
    ];

    public function storage() {
        return $this->belongsTo('Modules\Inventory\Entities\InventoryStorage', 'storage_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
