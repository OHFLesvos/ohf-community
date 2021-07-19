@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Knowledge Base'))
@section('site-title', __('Tags') . ' - ' . __('Knowledge Base'))

@section('content')
    <h1 class="display-4">{{ __('Tags') }}</h1>
    @if(! $tags->isEmpty())
        <div class="mb-4">
            <div class="columns-3">
                @foreach ($tags as $tag)
                    <a href="{{ route('kb.tag', $tag) }}">{{ $tag->name }}</a> ({{ $tag->wikiArticles()->count() }})
                    <br>
                @endforeach
            </div>
        </div>
        <p><small>{{ __('Found :num tags.', ['num' => $tags->count() ]) }}</small></p>
    @else
        <x-alert type="info">
            {{ __('No articles found.') }}
        </x-alert>
    @endif

@endsection
