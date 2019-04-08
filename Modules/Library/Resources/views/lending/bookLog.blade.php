@extends('layouts.app')

@section('title', __('library::library.library') . ': ' .__('library::library.book_lending_log'))

@section('content')

    <h2 class="mb-3">
        {{ $book->title }}
        <small class="d-block d-sm-inline">
            {{ $book->author }}@if(isset($book->author) && isset($book->isbn13)),@endif {{ $book->isbn }}
        </small>
    </h2>

    @if($lendings->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('people::people.person')</th>
                        <th>@lang('library::library.lent')</th>
                        <th>@lang('library::library.returned')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lendings as $lending)
                        <tr>
                            <td>
                                <a href="{{ route('library.lending.person', $lending->person) }}">{{ $lending->person->fullName}}</a>
                            </td>
                            <td>
                                {{ $lending->lending_date->toDateString() }}
                            </td>
                            <td>
                                {{ optional($lending->returned_date)->toDateString() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $lendings->links() }}
    @else
        @component('components.alert.info')
            @lang('library::library.not_lent_to_anyone_so_far')
        @endcomponent
    @endif

@endsection
