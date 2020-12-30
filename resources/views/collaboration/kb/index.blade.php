@extends('layouts.app', ['wide_layout' => false])

@section('title', __('kb.knowledge_base'))

@section('content')

    {!! Form::open(['route' => ['kb.index'], 'method' => 'get']) !!}
        <div class="input-group mb-3">
            {{ Form::search('search', isset($search) ? $search : null, [ 'class' => 'form-control'.(isset($search) && $article_results->isEmpty() ? ' focus-tail' : ''), 'placeholder' => __('app.search') . '...' ]) }}
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit"><x-icon icon="search"/></button>
                @if(isset($search))
                    <a class="btn btn-secondary" href="{{ route('kb.index', ['reset_search']) }}"><x-icon icon="eraser"/></a>
                @endif
            </div>
        </div>
    {!! Form::close() !!}

    @if (isset($search))
        @if(! $article_results->isEmpty())

            <p><small>@lang('wiki.found_num_articles_containing_word', ['num' => $article_results->total(), 'word' => $search ])</small></p>
            <div class="columns-3 mb-3">
                @foreach ($article_results as $article)
                    <a href="{{ route('kb.articles.show', $article) }}">{{ $article->title }}</a><br>
                @endforeach
            </div>
            {{ $article_results->links() }}

        @else
            <x-alert type="info">
                @lang('wiki.no_articles_found')
            </x-alert>
        @endif
    @else

        <div class="row">

            <div class="col-sm">

                {{-- Popular articles --}}
                @unless($popular_articles->isEmpty())
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            @lang('wiki.popular_articles')
                            <a href="{{ route('kb.articles.index', ['order' => 'popularity']) }}" class="float-right">@lang('app.show_all')</a>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach($popular_articles as $article)
                                <a href="{{ route('kb.articles.show', $article) }}" class="list-group-item list-group-item-action">
                                    {{ $article->title }}
                                    <small class="float-right text-muted">{{ $article->viewCount }}</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endunless

                {{-- Recently changed --}}
                @unless($recent_articles->isEmpty())
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            @lang('wiki.recenlty_update_articles')
                            <a href="{{ route('kb.articles.index', ['order' => 'recent']) }}" class="float-right">@lang('app.show_all')</a>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach($recent_articles as $article)
                                <a href="{{ route('kb.articles.show', $article) }}" class="list-group-item list-group-item-action">
                                    {{ $article->title }}
                                    <small class="float-right text-muted">{{ $article->updated_at->diffForHumans() }}</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endunless

            </div>

            @unless($popular_tags->isEmpty() && $featured_articles->isEmpty())
                <div class="col-sm">

                    {{-- Featured articles --}}
                    @unless($featured_articles->isEmpty())
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                @lang('wiki.featured_articles')
                                <a href="{{ route('kb.articles.index') }}" class="float-right">@lang('app.show_all')</a>
                            </div>
                            @unless($featured_articles->isEmpty())
                                <div class="list-group list-group-flush">
                                    @foreach($featured_articles as $article)
                                        <a href="{{ route('kb.articles.show', $article) }}" class="list-group-item list-group-item-action">
                                            {{ $article->title }}
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="card-body p-3">
                                    <em>@lang('wiki.no_articles_found')</em>
                                </div>
                            @endunless
                        </div>
                    @endunless

                    {{-- Popular Tags --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            @lang('app.popular_tags')
                            <a href="{{ route('kb.tags') }}" class="float-right">@lang('app.show_all')</a>
                        </div>
                        <div class="card-body p-3">
                            @forelse($popular_tags as $tag)
                                <a href="{{ route('kb.tag', $tag) }}">{{ $tag->name }}</a><small class="text-muted px-1">({{ $tag->wikiArticles()->count() }})</small>
                            @empty
                                <em>@lang('app.no_tags_defined')</em>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endunless
        </div>

    @endif

@endsection
