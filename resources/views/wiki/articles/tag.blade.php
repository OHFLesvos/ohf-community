@extends('layouts.app')

@section('title', __('wiki.articles'))

@section('content')

    @if( ! $articles->isEmpty() )
        <h2>@lang('app.tag'): {{ $tag->name }}</h2>
        @foreach ($articles as $article)
            <a href="{{ route('wiki.articles.show', $article) }}">{{ $article->title }}</a><br>
        @endforeach
        {{ $articles->links() }}
        <p class="mt-2"><small>{{ trans_choice('wiki.num_articles_in_total', $articles->total(), [ 'num' => $articles->total() ]) }}</small></p>
    @else
        @component('components.alert.info')
            @lang('wiki.no_articles_found')
        @endcomponent
	@endif
	
@endsection
