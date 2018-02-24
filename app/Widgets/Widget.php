<?php

namespace App\Widgets;

interface Widget
{
    function authorize(): bool;
    function view(): string;
    function args(): array;
}