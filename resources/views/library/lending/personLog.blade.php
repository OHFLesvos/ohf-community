@extends('layouts.app')

@section('title', __('library.library') . ': ' .__('library.person_lending_log'))

@section('content')

    <h2 class="mb-3">
        {{ $person->fullName }}
        <small class="d-block d-sm-inline">
            {{ $person->nationality }}@if(isset($person->nationality) && isset($person->date_of_birth)),@endif {{ $person->date_of_birth }}
        </small>
    </h2>

    @if($lendings->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('library.book')</th>
                        <th>@lang('library.lent')</th>
                        <th>@lang('library.returned')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lendings as $lending)
                        <tr>
                            <td>
                                <a href="{{ route('library.lending.book', $lending->book) }}">{{ $lending->book->title}}</a>
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
            @lang('library.no_books_lent_so_far')
        @endcomponent
    @endif

@endsection
