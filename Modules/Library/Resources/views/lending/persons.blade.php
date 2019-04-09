@extends('layouts.app')

@section('title', __('library::library.library') . ': ' . ucfirst(__('library::library.borrowers')))

@section('content')

    @if($persons->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('people::people.person')</th>
                        <th>@lang('library::library.books')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($persons as $person)
                        <tr class="@if($person->hasOverdueBookLendings) table-danger @endif">
                            <td>
                                <a href="{{ route('library.lending.person', $person) }}">{{ $person->fullName }}</a>
                            </td>
                            <td>{{ $person->bookLendings()->whereNull('returned_date')->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @component('components.alert.info')
            @lang('library::library.no_books_lent')
        @endcomponent
    @endif

@endsection