@extends('widgets.base', [
    'icon' => 'info-circle',
    'title' => __('Knowledge Base'),
    'href' => route('kb.index'),
])

@section('widget-content')
    @include('widgets.value-table', [
        'items' => [
            __('Articles in the database') => $num_articles,
        ],
    ])
    @isset($latest_article)
        <div class="card-body p-3 border-top">
            {{ __('The latest article is <a href=":href">:title</a>, added :date.', [
                'title' => $latest_article->title,
                'href' => route('kb.articles.show', $latest_article),
                'date' => $latest_article->created_at->diffForHumans(),
            ]) }}
    </div>
    @endisset
@endsection
