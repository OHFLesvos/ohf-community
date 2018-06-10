@extends('layouts.app')

@section('title', __('wiki.show_article'))

@section('content')

    <h1>{{ $article->title }}</h1>
	{!! $article->content !!}

    @if(count($article->tags) > 0)
        <p>
            <strong>@lang('app.tags'):</strong>
            @foreach($article->tags->sortBy('name') as $tag)
                <a href="{{ route('wiki.articles.tag', $tag) }}">{{ $tag->name }}</a>@if(!$loop->last), @endif
            @endforeach
        </p>
    @endif
@endsection
