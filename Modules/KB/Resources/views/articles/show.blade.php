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
        $audit = $article->audits()->with('user')->latest()->first();
    @endphp
    @isset($audit)
        <p>
            <small>
                @lang('app.updated_by_author_time_ago', ['author' => $audit->getMetadata()['user_name'], 'time' => (new Carbon\Carbon($audit->getMetadata()['audit_created_at']))->diffForHumans() ])
                @lang('app.viewed_num_times', ['num' => $article->viewCount ])
            </small>
        </p>
    @endif
@endsection
