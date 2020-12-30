<?php

namespace App\View\Widgets;

interface Widget
{
    public function authorize(): bool;
    public function render();
}
