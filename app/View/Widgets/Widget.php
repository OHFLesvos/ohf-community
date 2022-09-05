<?php

namespace App\View\Widgets;

interface Widget
{
    public function authorize(): bool;

    public function key(): string;

    public function data(): array;
}
