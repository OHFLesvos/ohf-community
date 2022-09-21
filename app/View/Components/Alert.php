<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    private const ICONS = [
        'danger' => 'exclamation-circle',
        'warning' => 'exclamation-triangle',
        'info' => 'info-circle',
        'success' => 'check',
    ];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public readonly string $type,
        public ?string $icon = null,
        public readonly ?bool $dismissible = false)
    {
        if (! isset(self::ICONS[$type])) {
            throw new \Exception('Alert parameter $type must be one of ['.implode(', ', array_keys(self::ICONS)).']');
        }

        $this->icon = $icon ?? self::ICONS[$type];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.alert');
    }
}
