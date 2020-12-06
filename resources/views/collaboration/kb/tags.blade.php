@extends('layouts.app')

@section('title', __('app.tags'))

@section('content')

    @if(! $tags->isEmpty())
        <p><small>@lang('app.found_num_tags', ['num' => $tags->count() ])</small></p>
        <div class="mb-4">
            <div class="columns-3">
                @foreach ($tags as $tag)
                    <a href="{{ route('kb.tag', $tag) }}">{{ $tag->name }}</a> ({{ $tag->wikiArticles()->count() }})
                    <br>
                @endforeach
            </div>
        </div>
    @else
        <x-alert type="info">
            @lang('wiki.no_articles_found')
        </x-alert>
    @endif

@endsection
