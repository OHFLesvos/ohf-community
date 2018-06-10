<div class="card mb-4">
    <div class="card-header">
        @lang('wiki.wiki')
        <a class="pull-right" href="{{ route('wiki.articles.index')  }}">@lang('app.view')</a>
    </div>
    <div class="card-body pb-2">
        <p>
            {{ trans_choice('wiki.articles_in_db', $num_articles, [ 'num' => $num_articles ]) }}
            @isset($latest_article)
                <br>
                @lang('wiki.the_latest_article_is')
                <a href="{{ route('wiki.articles.show', $latest_article) }}">{{ $latest_article->title }}</a>
                <small>(@lang('app.edited') {{ $latest_article->updated_at->diffForHumans() }})</small>.
            @endisset
        </p>
    </div>
</div>
