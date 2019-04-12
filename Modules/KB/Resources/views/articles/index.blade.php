@extends('layouts.app')

@section('title', __('kb::wiki.articles'))

@section('content')

    @if( ! $articles->isEmpty() )
        <div class="mb-4">
            <div class="columns-3 mb-3">
                @foreach ($articles as $article)
                    <a href="{{ route('kb.articles.show', $article) }}">{{ $article->title }}</a><br>
                @endforeach
            </div>
            {{ $articles->links() }}
            <p class="mt-2"><small>{{ trans_choice('kb::wiki.num_articles_in_total', $articles->total(), [ 'num' => $articles->total() ]) }}</small></p>
        </div>
    @else
        @component('components.alert.info')
            @lang('kb::wiki.no_articles_found')
        @endcomponent
	@endif
	
@endsection
