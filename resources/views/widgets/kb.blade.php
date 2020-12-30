@extends('widgets.base', [
    'icon' => 'info-circle',
    'title' => __('kb.knowledge_base'),
    'href' => route('kb.index'),
])

@section('widget-content')
    @include('widgets.value-table', [
        'items' => [
            __('Articles in the database') => $num_articles,
        ],
    ])
    <div class="card-body p-3 border-top">
        @isset($latest_article)
            @lang('wiki.the_latest_article_is', [
                'title' => $latest_article->title,
                'href' => route('kb.articles.show', $latest_article),
                'date' => $latest_article->created_at->diffForHumans(),
            ])
        @endisset
    </div>
@endsection
