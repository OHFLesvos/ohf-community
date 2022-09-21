<?php

namespace App\Models;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Storage;

class User extends Authenticatable implements HasLocalePreference
{
    use HasFactory;
    use Notifiable;
    use NullableFields;

    protected $nullable = [
        'tfa_secret',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
        'locale',
        'provider_name',
        'provider_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super_admin' => 'boolean',
    ];

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->locale;
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin;
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withTimestamps();
    }

    public function hasRole(int $id): bool
    {
        return $this->roles()
            ->where('roles.id', $id)
            ->exists();
    }

    /**
     * The users that belong to the role.
     */
    public function administeredRoles()
    {
        return $this->belongsToMany(Role::class, 'role_admin')
            ->withTimestamps();
    }

    public function hasPermission($permissionKey)
    {
        return $this->roles->contains(
            fn ($role) => $role->permissions->contains(
                fn ($value) => $value->key === $permissionKey
            )
        );
    }

    /**
     * Returns a collection of the keys of all permissions this users possesses
     *
     * @return Collection
     */
    public function permissions(): Collection
    {
        $permissions = [];
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }

        return collect(config('permissions.keys'))
            ->keys()
            ->intersect(collect($permissions)->map(fn ($permission) => $permission->key))
            ->unique()
            ->values();
    }

    public function avatarUrl(?int $size = null): string
    {
        if (blank($this->avatar)) {
            return route('users.avatar', [$this, 'size' => $size]);
        }

        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        return Storage::url($this->avatar);
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

        return $query->where('name', 'LIKE', '%'.$value.'%')
            ->orWhere('email', 'LIKE', '%'.$value.'%');
    }
}
