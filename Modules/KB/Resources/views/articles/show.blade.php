@extends('layouts.app')

@section('title', __('kb::wiki.show_article'))

@section('content')

    <h1>{{ $article->title }}</h1>
	{!! $article->content !!}

    <hr>
    @if(count($article->tags) > 0)
        <p>
            <strong>@lang('app.tags'):</strong>
            @foreach($article->tagsSorted as $tag)
                <a href="{{ route('kb.tag', $tag) }}">{{ $tag->name }}</a>@if(!$loop->last), @endif
            @endforeach
        </p>
    @endif

    @php
        try {
            $audit = $article->audits()->with('user')->latest()->first();
            $metadata = $audit->getMetadata();
        } catch (\ErrorException $e) {
            Log::error('Unable to get audit metadata for article.', $e);
        }
    @endphp
    @isset($metadata)
        <p>
            <small>
                @lang('app.updated_by_author_time_ago', ['author' => $metadata['user_name'], 'time' => (new Carbon\Carbon($metadata['audit_created_at']))->diffForHumans() ])
                @lang('app.viewed_num_times', ['num' => $article->viewCount ])
            </small>
        </p>
    @endif
@endsection
