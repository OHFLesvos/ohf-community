<?php

namespace App\View\Components;

use Illuminate\View\Component;

class OauthButtons extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.oauth-buttons', [
            'oauth_services' => $this->getOauthServices(),
        ]);
    }

    private function getOauthServices(): array
    {
        return collect(config('auth.socialite.drivers'))
            ->filter(fn (string $driver) => config('services.'.$driver) !== null
                && array_elements_not_blank(config('services.'.$driver), ['client_id', 'client_secret', 'redirect']))
            ->map(fn (string $driver) => [
                'name' => $driver,
                'icon' => $driver,
                'label' => __('Sign in with :service', [
                    'service' => ucfirst($driver),
                ]),
            ])
            ->toArray();
    }
}
