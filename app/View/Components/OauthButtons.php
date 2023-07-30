<?php

namespace App\View\Components;

use App\Util\ArrayUtil;
use Illuminate\View\Component;

class OauthButtons extends Component
{
    public function __construct(public bool $signUp = false)
    {
    }

    public function render(): \Illuminate\Contracts\View\View|\Closure|string
    {
        return view('components.oauth-buttons', [
            'oauth_services' => $this->getOauthServices(),
        ]);
    }

    private function getOauthServices(): array
    {
        return collect(config('auth.socialite.drivers'))
            ->filter(fn (string $driver) => config('services.'.$driver) !== null
                && ArrayUtil::elementsNotBlank(config('services.'.$driver), ['client_id', 'client_secret', 'redirect']))
            ->map(fn (string $driver) => [
                'name' => $driver,
                'icon' => $driver,
                'label' => $this->signUp
                    ? __('Sign up with :service', [
                        'service' => ucfirst($driver),
                    ])
                    : __('Sign in with :service', [
                        'service' => ucfirst($driver),
                    ]),
            ])
            ->toArray();
    }
}
