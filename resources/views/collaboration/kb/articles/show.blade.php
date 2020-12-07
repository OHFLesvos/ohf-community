@extends('layouts.app', ['wide_layout' => false])

@section('title', __('kb.knowledge_base'))

@section('content')
    <h1>{{ $article->title }}</h1>
    @if($article->public)
        @auth
            <p><em><small class="text-muted"><x-icon icon="eye"/> @lang('wiki.article_publicly_available')</small></em></p>
        @endauth
    @endif
    {!! $article->content !!}
    <hr>
    @auth
        @if(count($article->tags) > 0)
            <p>
                <strong>@lang('app.tags'):</strong>
                @foreach($article->tagsSorted as $tag)
                    <a href="{{ route('kb.tag', $tag) }}">{{ $tag->name }}</a>@if(!$loop->last), @endif
                @endforeach
            </p>
        @endif
    @endauth
    <p>
        <small>
            @auth
                @php
                    try {
                        $audit = $article->audits()->with('user')->latest()->first();
                        $metadata = optional($audit)->getMetadata();
                    } catch (\ErrorException $e) {
                        Log::error('Unable to get audit metadata for article \'' . $article->title .'\' (ID: '.$article->id.'): ' . $e->getMessage());
                    }
                @endphp
                @isset($metadata)
                    @lang('app.updated_by_author_time_ago', ['author' => $metadata['user_name'], 'time' => (new Carbon\Carbon($metadata['audit_created_at']))->diffForHumans() ])
                @endisset
            @else
                @lang('app.updated_time_ago', ['time' => (new Carbon\Carbon($article->updated_at))->diffForHumans()])
            @endauth
            <br class="d-block d-sm-none">
            @lang('app.viewed_num_times', ['num' => $article->viewCount ])
        </small>
    </p>
@endsection
