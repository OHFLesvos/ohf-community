@extends('layouts.app')

@section('title', __('app.tag') . ': ' . $tag->name)

@section('content')

    @if( ! $articles->isEmpty() )
        <p><small>@lang('kb::wiki.found_num_articles_with_tag', ['num' => $articles->total(), 'tag' => $tag->name ])</small></p>
        @foreach ($articles as $article)
            <a href="{{ route('kb.articles.show', $article) }}">{{ $article->title }}</a>
                @if($article->public)
                    <small class="text-muted" title="@lang('kb::wiki.article_publicly_available')">@icon(eye)</small>
                @endif
            <br>
        @endforeach
        {{ $articles->links() }}
    @else
        @component('components.alert.info')
            @lang('kb::wiki.no_articles_found')
        @endcomponent
	@endif
	
@endsection
