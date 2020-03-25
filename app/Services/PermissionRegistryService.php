<?php

namespace App\Services;

class PermissionRegistryService
{
    private $permissions = [];

    public function define(string $key, string $label, bool $sensitive)
    {
        $this->permissions[$key] = [
            'label' => $label,
            'sensitive' => $sensitive,
        ];
    }

    public function collection(bool $sensitiveOnly = false)
    {
        return collect($this->permissions)
            ->filter(fn ($p) => ! $sensitiveOnly || $p['sensitive'])
            ->map(fn ($p) => __($p['label']));
    }

    public function keys()
    {
        return collect($this->permissions)
            ->keys()
            ->toArray();
    }

    public function hasKey(string $key, bool $sensitive = false)
    {
        if ($sensitive) {
            return collect($this->permissions)
                ->where('sensitive', true)
                ->keys()
                ->contains($key);
        }
        return isset($this->permissions[$key]);
    }

    public function getCategorizedPermissions()
    {
        $map = $this->collection()->toArray();
        $permissions = [];
        foreach ($map as $k => $v) {
            if (preg_match('/^(.+): (.+)$/', $v, $m)) {
                $permissions[$m[1]][$k] = $m[2];
            } else {
                $permissions[null][$k] = $v;
            }
        }
        ksort($permissions);
        return $permissions;
    }
}
