@extends('layouts.app')

@section('title', __('library.library') . ': ' .__('library.book'))

@section('content')

    <h2 class="mb-3">
        {{ $book->title }}
        <small class="d-block d-sm-inline">{{ $book->author }}@isset($book->isbn), {{ $book->isbn13 }}@endisset
        </small>
    </h2>

    @php
        $lendings = $book->lendings()
            ->orderBy('return_date', 'desc')
            ->orderBy('created_at', 'desc');
    @endphp
    @if($lendings->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('people.person')</th>
                        <th class="d-none d-sm-table-cell">@lang('library.lending')</th>
                        <th>@lang('library.returned')</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lendings->get() as $lending)
                        <tr class="@if($lending->returned_date == null)@if($lending->return_date->lt(Carbon\Carbon::today()))table-danger @elseif($lending->return_date->eq(Carbon\Carbon::today()))table-warning @else table-info @endif @endif">
                            <td class="align-middle">
                                <a href="{{ route('library.lending.person', $lending->person) }}">{{ $lending->person->fullName}}</a>
                            </td>
                            <td class="align-middle d-none d-sm-table-cell">
                                {{ $lending->lending_date->toDateString() }}
                            </td>
                            <td class="align-middle">
                                {{ optional($lending->returned_date)->toDateString() }}
                            </td>
                            <td class="fit">
                                @if($lending->returned_date == null)
                                    <form action="{{ route('library.lending.returnBook', $lending->person) }}" method="post" class="d-inline">
                                        {{ csrf_field() }}
                                        {{ Form::hidden('book_id', $book->id) }}
                                        <button type="submit" class="btn btn-sm btn-success">
                                            @icon(inbox)<span class="d-none d-sm-inline"> @lang('library.return')</span>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-primary">
                                        @icon(calendar-plus-o)<span class="d-none d-sm-inline"> @lang('library.extend')</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @component('components.alert.info')
            @lang('library.no_books_lent_so_far')
        @endcomponent
    @endif

@endsection

@section('script')
    function toggleSubmit() {
        if ($('#book_id').val()) {
            $('#lend-existing-book-button').attr('disabled', false);
        } else {
            $('#lend-existing-book-button').attr('disabled', true);
        }
    }
    $(function(){
        $('#book_id').on('change', toggleSubmit);
        toggleSubmit();
    });
@endsection
