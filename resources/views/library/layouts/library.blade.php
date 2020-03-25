@extends('layouts.tabbed_view', [
    'nav_elements' => [
        [
            'url' => route('library.lending.index'),
            'label' => __('library.lending'),
            'icon' => 'inbox',
            'active' => fn ($currentRouteName) => $currentRouteName == 'library.lending.index',
            'authorized' => Gate::allows('operate-library')
        ],
        [
            'url' => route('library.books.index'),
            'label' => __('library.books'),
            'icon' => 'book',
            'active' => fn ($currentRouteName) => $currentRouteName == 'library.books.index',
            'authorized' => Gate::allows('operate-library')
        ]
    ]
])