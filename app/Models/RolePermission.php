<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    protected $touches = ['role'];

    protected $fillable = ['key'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function withKey(string $key): RolePermission
    {
        $this->key = $key;

        return $this;
    }
}
