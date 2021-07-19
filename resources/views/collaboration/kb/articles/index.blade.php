@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Knowledge Base'))
@section('site-title', __('Articles') . ' - ' . __('Knowledge Base'))

@section('content')
    <h1 class="display-4">
        @if($order == 'popularity')
            {{ __('Articles sorted by popularity') }}
        @elseif($order == 'recent')
            {{ __('Articles sorted by modification date') }}
        @else
            {{ __('Articles') }}
        @endif
    </h1>
    @if(! $articles->isEmpty())
        <div class="columns-2 mb-3">
            @foreach ($articles as $article)
                <a href="{{ route('kb.articles.show', $article) }}">{{ $article->title }}</a>
                @if($article->public)
                    <small class="text-muted" title="{{ __('This article is publicly available.') }}"><x-icon icon="eye"/></small>
                @endif
                @if($order == 'popularity')
                    <small class="text-muted d-block d-sm-inline">{{ __(':num views', ['num' => $article->viewCount ]) }}</small>
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
                        {{ trans_choice('One article in total.|:num articles in total.', $articles->total(), [ 'num' => $articles->total() ]) }}
                    </small>
                </p>
            </div>
        </div>
    @else
        <x-alert type="info">
            {{ __('No articles found.') }}
        </x-alert>
    @endif
@endsection
