@extends('layouts.app')

@section('title', __('app.report'))

@section('content')

    <div class="row">
        <div class="col-sm">

            <h2>
                @lang('library.borrowers')
                <small>{{ $borrwer_count }}</small>
            </h2>

            @if($borrwer_count > 0)

                {{-- Popular --}}
                <h3>@lang('app.popular')</h3>
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>@lang('app.title')</th>
                            <th>@lang('people.age')</th>
                            <th>@lang('people.nationality')</th>
                            <th class="text-center">@lang('people.gender')</th>
                            <th class="text-right"># @lang('library.lendings')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrwer_lendings_top as $borrower)
                            <tr>
                                <td>
                                    @can('view', $borrower)
                                        {{ $borrower->fullName }}
                                    @else
                                        {{ substr($borrower->name, 0, 1) }}.
                                        {{ substr($borrower->family_name, 0, 1) }}.
                                    @endcan
                                </td>
                                <td>{{ $borrower->age }}</td>
                                <td>{{ $borrower->nationality }}</td>
                                <td class="text-center">
                                    @if($borrower->gender == 'm')@icon(male)@endif
                                    @if($borrower->gender == 'f')@icon(female)@endif
                                </td>
                                <td class="text-right">{{ $borrower->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Nationalities --}}
                <h3>@lang('people.nationalities')</h3>
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>@lang('app.country')</th>
                            <th class="text-right">@lang('app.quantity')</th>
                            <th class="text-right">@lang('app.percentage')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrwer_nationalities as $nationality)
                            <tr>
                                <td>{{ $nationality->nationality ?? __('app.unspecified') }}</td>
                                <td class="text-right">{{ $nationality->quantity }}</td>
                                <td class="text-right">{{ round($nationality->quantity / $borrwer_count * 100, 1) }} %</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Genders --}}
                <h3>@lang('people.gender')</h3>
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>@lang('people.gender')</th>
                            <th class="text-right">@lang('app.quantity')</th>
                            <th class="text-right">@lang('app.percentage')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrwer_genders as $gender)
                            <tr>
                                <td>
                                    @if($gender->gender == 'm')@icon(male)@endif
                                    @if($gender->gender == 'f')@icon(female)@endif
                                </td>
                                <td class="text-right">{{ $gender->quantity }}</td>
                                <td class="text-right">{{ round($gender->quantity / $borrwer_count * 100, 1) }} %</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif

        </div>
        <div class="col-sm">

            <h2>
                @lang('library.books')
                <small>{{ $book_count }}</small>
            </h2>


            {{-- Popular --}}
            @if(count($book_lendings_top) > 0)
                <h3>@lang('app.popular')</h3>
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>@lang('app.title')</th>
                            <th>@lang('library.author')</th>
                            <th>@lang('app.language')</th>
                            <th class="text-right"># @lang('library.lendings')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($book_lendings_top as $book)
                            <tr>
                                <td>{{ Str::limit($book->title, 40) }}</td>
                                <td>{{ Str::limit($book->author, 30) }}</td>
                                <td>{{ $book->language }}</td>
                                <td class="text-right">{{ $book->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Languages --}}
            @if($book_count > 0)
                <h3>@lang('app.languages')</h3>
                @php
                    $undefined_count = $book_languages->filter(fn ($l) => $l->language_code === null)->sum('quantity');
                @endphp
                @if($undefined_count > 0)
                    <p><em>@lang('library.books_without_language_specified', [ 'count' => $undefined_count, 'percentage' => round($undefined_count / $book_count * 100, 1) ])</em></p>
                @endif
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>@lang('app.language')</th>
                            <th class="text-right">@lang('app.quantity')</th>
                            <th class="text-right">@lang('app.percentage')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($book_languages->filter(fn ($l) => $l->language_code !== null) as $language)
                            <tr>
                                <td>{{ $language->language ?? __('app.unspecified') }}</td>
                                <td class="text-right">{{ $language->quantity }}</td>
                                <td class="text-right">{{ round($language->quantity / ($book_count - $undefined_count) * 100, 1) }} %</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif

        </div>
    </div>

@endsection

