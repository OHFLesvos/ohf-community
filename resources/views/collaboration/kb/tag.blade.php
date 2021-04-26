@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Knowledge Base'))
@section('site-title', __('Tag') . ': ' . $tag->name . ' - ' . __('Knowledge Base'))


@section('content')
    <h1 class="display-4">@lang('Tag') <em>{{ $tag->name }}</em></h1>
    @if(! $articles->isEmpty())
        <p>
            @foreach ($articles as $article)
                <a href="{{ route('kb.articles.show', $article) }}">{{ $article->title }}</a>
                    @auth
                        @if($article->public)
                            <small class="text-muted" title="@lang('This article is publicly available.')"><x-icon icon="eye"/></small>
                        @endif
                    @endauth
                <br>
            @endforeach
        </p>
        {{ $articles->links() }}
        <p><small>@lang('Found :num articles with tag <em>:tag</em>.', ['num' => $articles->total(), 'tag' => $tag->name ])</small></p>
        @if($has_more_articles)
            <x-alert type="info">
                @guest
                    @lang('Please <a href=":url">login</a> to see more articles.', ['url' => route('login') ])
                @else
                    @lang('You do not have sufficient permissions to view all articles.')
                @endguest
            </x-alert>
        @endif
    @else
        <x-alert type="info">
            @lang('No articles found.')
        </x-alert>
    @endif

@endsection
