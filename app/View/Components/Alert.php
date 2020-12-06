<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public string $type;
    public string $icon;
    public bool $dismissible;

    private array $icons = [
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
    public function __construct(string $type, ?string $icon = null, ?bool $dismissible = false)
    {
        assert(isset($this->icons[$type]), 'Alert parameter $type must be one of [' . implode(', ', array_keys($this->icons)) . ']');

        $this->type = $type;
        $this->icon = $icon ?? $this->icons[$this->type];
        $this->dismissible = $dismissible;
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
