@extends('layouts.tabbed_view', [ 
    'nav_elements' => [
        [
            'url' => route('library.lending.index'),
            'label' => __('library.lending'),
            'icon' => 'inbox',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'library.lending.index';
            },
            'authorized' => Gate::allows('operate-library')
        ],
        [
            'url' => route('library.books.index'),
            'label' => __('library.books'),
            'icon' => 'book',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'library.books.index';
            },
            'authorized' => Gate::allows('operate-library')
        ]
    ] 
])