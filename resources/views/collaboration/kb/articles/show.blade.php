@extends('layouts.app', ['wide_layout' => false])

@section('title', __('kb.knowledge_base'))
@section('site-title', $article->title . ' - ' . __('kb.knowledge_base'))

@section('content')
    <h1 class="display-4">{{ $article->title }}</h1>
    <div class="row">
        <div class="col-md-8">
            {!! $article->content !!}
            <hr class="d-md-none">
        </div>
        <div class="col-md-3 offset-md-1">
            @if($article->public)
                @auth
                    <h5>@lang('Visibility')</h5>
                    <p><x-icon icon="eye"/> @lang('wiki.article_publicly_available')</p>
                @endauth
            @endif
            @auth
                @if(count($article->tags) > 0)
                    <h5>@lang('app.tags')</h5>
                    <p>
                        @foreach($article->tagsSorted as $tag)
                            <a href="{{ route('kb.tag', $tag) }}">{{ $tag->name }}</a>@if(!$loop->last), @endif
                        @endforeach
                    </p>
                @endif
            @endauth
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
                    <h5>@lang('Last change')</h5>
                    <p>@lang('app.updated_by_author_time_ago', ['author' => $metadata['user_name'], 'time' => (new Carbon\Carbon($metadata['audit_created_at']))->diffForHumans() ])</p>
                @endisset
            @else
                @lang('app.updated_time_ago', ['time' => (new Carbon\Carbon($article->updated_at))->diffForHumans()])
            @endauth
            <h5>@lang('Statistics')</h5>
            <p>@lang('app.viewed_num_times', ['num' => $article->viewCount ])</p>
        </div>
    </div>
@endsection
