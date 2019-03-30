@extends('layouts.app')

@section('title', __('wiki::wiki.show_article'))

@section('content')

    <h1>{{ $article->title }}</h1>
	{!! $article->content !!}

    <hr>
    @if(count($article->tags) > 0)
        <p>
            <strong>@lang('app.tags'):</strong>
            @foreach($article->tags->sortBy('name') as $tag)
                <a href="{{ route('wiki.articles.tag', $tag) }}">{{ $tag->name }}</a>@if(!$loop->last), @endif
            @endforeach
        </p>
    @endif

    @php
        $audit = $article->audits()->with('user')->latest()->first();
    @endphp
    @isset($audit)
        <p><small><span title="{{ $audit->getMetadata()['audit_created_at'] }}">{{ (new Carbon\Carbon($audit->getMetadata()['audit_created_at']))->diffForHumans() }}</span> @lang('app.updated_by') {{ $audit->getMetadata()['user_name'] }}</small></p>
    @endif
@endsection
