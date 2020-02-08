@php
    $links = [
        [
            'url' => route('kb.index'),
            'title' => __('app.view'),
            'icon' => 'search',
            'authorized' => true,
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('collaboration::kb.knowledge_base'))

@section('widget-content')
    <div class="card-body pb-2">
        <p>
            {{ trans_choice('collaboration::wiki.articles_in_db', $num_articles, [ 'num' => $num_articles ]) }}
            @isset($latest_article)
                <br>
                @lang('collaboration::wiki.the_latest_article_is')
                <a href="{{ route('kb.articles.show', $latest_article) }}">{{ $latest_article->title }}</a>
                <small>(@lang('app.edited') {{ $latest_article->updated_at->diffForHumans() }})</small>.
            @endisset
        </p>
    </div>
@endsection
