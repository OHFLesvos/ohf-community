@extends('layouts.app')

@section('title', __('app.tag') . ': ' . $tag->name)

@section('content')

    @if( ! $articles->isEmpty() )
        <p><small>@lang('collaboration::wiki.found_num_articles_with_tag', ['num' => $articles->total(), 'tag' => $tag->name ])</small></p>
        <p>
            @foreach ($articles as $article)
                <a href="{{ route('kb.articles.show', $article) }}">{{ $article->title }}</a>
                    @auth 
                        @if($article->public)
                            <small class="text-muted" title="@lang('collaboration::wiki.article_publicly_available')">@icon(eye)</small>
                        @endif
                    @endauth
                <br>
            @endforeach
        </p>
        {{ $articles->links() }}
        @if($has_more_articles)
            @component('components.alert.info')
                @guest
                    @lang('collaboration::wiki.please_login_to_see_more_articles', ['url' => route('login') ])
                @else
                    @lang('collaboration::wiki.you_do_not_have_sufficient_permissions_to_view_all_articles')
                @endguest
            @endcomponent
        @endif
    @else
        @component('components.alert.info')
            @lang('collaboration::wiki.no_articles_found')
        @endcomponent
	@endif
	
@endsection
