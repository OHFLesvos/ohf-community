@extends('layouts.app')

@section('title', __('app.tag') . ': ' . $tag->name)

@section('content')

    @if(! $articles->isEmpty())
        <p><small>@lang('wiki.found_num_articles_with_tag', ['num' => $articles->total(), 'tag' => $tag->name ])</small></p>
        <p>
            @foreach ($articles as $article)
                <a href="{{ route('kb.articles.show', $article) }}">{{ $article->title }}</a>
                    @auth
                        @if($article->public)
                            <small class="text-muted" title="@lang('wiki.article_publicly_available')"><x-icon icon="eye"/></small>
                        @endif
                    @endauth
                <br>
            @endforeach
        </p>
        {{ $articles->links() }}
        @if($has_more_articles)
            <x-alert type="info">
                @guest
                    @lang('wiki.please_login_to_see_more_articles', ['url' => route('login') ])
                @else
                    @lang('wiki.you_do_not_have_sufficient_permissions_to_view_all_articles')
                @endguest
            </x-alert>
        @endif
    @else
        <x-alert type="info">
            @lang('wiki.no_articles_found')
        </x-alert>
    @endif

@endsection
