@extends('library.layouts.library')

@section('title', __('library.library'))

@section('wrapped-content')

    @if($books->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('library.book')</th>
                        <th>@lang('library.author')</th>
                        <th class="d-none d-sm-table-cell">@lang('library.isbn')</th>
                        <th class="d-none d-sm-table-cell">@lang('app.language')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td>
                                <a href="{{ route('library.lending.book', $book) }}">
                                    {{ $book->title }}
                                </a>
                            </td>
                            <td>{{ $book->author }}</td>
                            <td class="d-none d-sm-table-cell">{{ $book->isbn }}</td>
                            <td class="d-none d-sm-table-cell">{{ $book->language }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $books->links() }}
        <p><small>@lang('library.num_books_in_total', [ 'books' => $num_books ])</small></p>
    @else
        @component('components.alert.info')
            @lang('library.no_books_registered')
        @endcomponent
    @endif

@endsection
