<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The users that belong to the role.
     */
    public function users()
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

    /**
     * The users that belong to the role.
     */
    public function administrators()
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

    /**
     * The users that belong to the role.
     */
    public function permissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    /**
     * Scope a query to only include records matching the filter.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFiltered($query, ?string $filter = '')
    {
        $value = trim($filter);
        if ($value == '') {
            return $query;
        }

        return $query->where('name', 'LIKE', '%'.$value.'%');
    }
}
