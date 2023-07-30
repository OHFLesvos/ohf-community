<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function hasUser(int $id): bool
    {
        return $this->users()
            ->where('users.id', $id)
            ->exists();
    }

    public function administrators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_admin')
            ->withTimestamps();
    }

    public function hasAdministrator(int $id): bool
    {
        return $this->administrators()
            ->where('users.id', $id)
            ->exists();
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(RolePermission::class);
    }
}
