@extends('layouts.app', ['wide_layout' => false])

@section('title', __('kb.knowledge_base'))
@section('site-title', __('app.tags') . ' - ' . __('kb.knowledge_base'))

@section('content')
    <h1 class="display-4">@lang('app.tags')</h1>
    @if(! $tags->isEmpty())
        <div class="mb-4">
            <div class="columns-3">
                @foreach ($tags as $tag)
                    <a href="{{ route('kb.tag', $tag) }}">{{ $tag->name }}</a> ({{ $tag->wikiArticles()->count() }})
                    <br>
                @endforeach
            </div>
        </div>
        <p><small>@lang('app.found_num_tags', ['num' => $tags->count() ])</small></p>
    @else
        <x-alert type="info">
            @lang('wiki.no_articles_found')
        </x-alert>
    @endif

@endsection
