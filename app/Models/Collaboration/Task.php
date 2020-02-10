<?php

namespace App\Models\Collaboration;

use App\User;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [ 'description' ];

    public function scopeOpen($query)
    {
        return $query->where('done_date', null);
    }

    public function scopeWithOwner($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
