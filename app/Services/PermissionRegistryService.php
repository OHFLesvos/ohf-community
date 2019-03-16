<?php

namespace App\Services;

class PermissionRegistryService {

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
            ->filter(function($p) use($sensitiveOnly) {
                return !$sensitiveOnly || $p['sensitive'];
            })
            ->map(function($p){
                return __($p['label']);
            });
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

}
