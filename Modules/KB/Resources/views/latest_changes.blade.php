@extends('layouts.app')

@section('title', __('app.latest_changes'))

@section('content')

    @if( ! $audits->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.date')</th>
                        <th>@lang('app.author')</th>
                        <th>@lang('app.type')</th>
                        <th>@lang('kb::wiki.article')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($audits as $audit)
                        <tr>
                            <td title="{{ $audit->created_at }}">{{ $audit->created_at->diffForHumans() }}</td>
                            <td>{{ optional($audit->user)->name }}</td>
                            <td>{{ $audit->event }}</td>
                            <td>
                                @php
                                    $mod = $audit->getModified();
                                    $title_mod = isset($mod['title']) ? $mod['title'] : null;
                                    $article = Modules\KB\Entities\WikiArticle::find($audit->auditable_id);
                                    $title = isset($title_mod) ? isset($title_mod['new']) ? $title_mod['new'] : $title_mod['old'] : ($article != null ? $article->title : '');
                                @endphp
                                @isset($article)<a href="{{ route('kb.articles.show', $article) }}">@endisset
                                {{ $title }}
                                @isset($article)</a>@endisset
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $audits->links() }}
    @else
        @component('components.alert.info')
            @lang('kb::wiki.no_articles_found')
        @endcomponent
	@endif
	
@endsection
