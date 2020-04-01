<?php

namespace App;

use App\Models\Collaboration\Task;
use App\Support\Facades\PermissionRegistry;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements HasLocalePreference
{
    use Notifiable;
    use NullableFields;

    protected $nullable = [
        'tfa_secret',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
        'locale',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'provider_name',
        'provider_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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

    public function isSuperAdmin() {
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

    /**
     * The users that belong to the role.
     */
    public function administeredRoles()
    {
        return $this->belongsToMany(Role::class, 'role_admin')
            ->withTimestamps();
    }

    public function hasPermission($permissionKey) {
        return $this->roles->contains(
            fn ($role) => $role->permissions->contains(
                fn ($value) => $value->key === $permissionKey
            )
        );
    }

    public function permissions() {
        $permissions = [];
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }
        return collect($permissions)
            ->filter(fn ($permission) => PermissionRegistry::hasKey($permission->key))
            ->unique();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function avatarUrl(?string $profile = null): string
    {
        return $this->avatar !== null ? $this->avatar : \Gravatar::get($this->email, $profile);
    }
}
