<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;

class Avatar extends Component
{
    const DEFAULT_SIZE = 48;

    public User $user;
    public int $size;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(User $user, ?int $size = self::DEFAULT_SIZE)
    {
        $this->user = $user;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.avatar');
    }
}
