@extends('layouts.app')

@section('title', __('app.tags'))

@section('content')

    @if( ! $tags->isEmpty() )
        <div class="mb-4">
            <div class="columns-3">
                @foreach ($tags as $tag)
                    <a href="{{ route('kb.articles.tag', $tag) }}">{{ $tag->name }}</a> ({{ $tag->wikiArticles()->count() }})
                    <br>
                @endforeach
            </div>
        </div>
    @else
        @component('components.alert.info')
            @lang('kb::wiki.no_articles_found')
        @endcomponent
	@endif
	
@endsection
