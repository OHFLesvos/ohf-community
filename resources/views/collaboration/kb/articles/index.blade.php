@extends('layouts.app', ['wide_layout' => false])

@section('title', __('wiki.articles'))

@section('content')
    @if(! $articles->isEmpty())
        @if($order == 'popularity')
            <p><small>@lang('wiki.showing_articles_by_popularity')</small></p>
        @elseif($order == 'recent')
            <p><small>@lang('wiki.showing_articles_by_modification_date')</small></p>
        @endif
        <div class="columns-3 mb-3">
            @foreach ($articles as $article)
                <a href="{{ route('kb.articles.show', $article) }}">{{ $article->title }}</a>
                @if($article->public)
                    <small class="text-muted" title="@lang('wiki.article_publicly_available')"><x-icon icon="eye"/></small>
                @endif
                @if($order == 'popularity')
                    <small class="text-muted d-block d-sm-inline">@lang('app.num_views', ['num' => $article->viewCount ])</small>
                @elseif($order == 'recent')
                    <small class="text-muted">{{ $article->updated_at->diffForHumans() }}</small>
                @endif
                <br>
            @endforeach
        </div>
        <div class="row alisgn-items-center">
            <div class="col-sm">
                {{ $articles->appends(['order' => $order])->links() }}
            </div>
            <div class="col-sm-auto pt-1">
                <p>
                    <small class="text-muted">
                        {{ trans_choice('wiki.num_articles_in_total', $articles->total(), [ 'num' => $articles->total() ]) }}
                    </small>
                </p>
            </div>
        </div>
    @else
        <x-alert type="info">
            @lang('wiki.no_articles_found')
        </x-alert>
    @endif
@endsection
