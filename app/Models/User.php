<?php

namespace App\Models;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'provider_name',
        'provider_id',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
        'locale',
        'provider_name',
        'provider_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super_admin' => 'boolean',
    ];

    public function preferredLocale(): string
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
    public function roles(): BelongsToMany
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
    public function administeredRoles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_admin')
            ->withTimestamps();
    }

    public function hasPermission($permissionKey): bool
    {
        return $this->roles->contains(
            fn ($role) => $role->permissions->contains(
                fn ($value) => $value->key === $permissionKey
            )
        );
    }

    /**
     * Returns a collection of the keys of all permissions this users possesses
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

    public function avatarUrl(): ?string
    {
        if (blank($this->avatar)) {
            return null;
        }

        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        return Storage::url($this->avatar);
    }
}
