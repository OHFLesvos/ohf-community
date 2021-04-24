@extends('layouts.app', ['wide_layout' => false])

@section('title', __('app.knowledge_base'))
@section('site-title', __('app.latest_changes') . ' - ' . __('app.knowledge_base'))

@section('content')
    <h1 class="display-4">@lang('app.latest_changes')</h1>
    @if(! $audits->isEmpty())
        <div class="table-responsive">
            <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <th>@lang('app.date')</th>
                        <th>@lang('app.author')</th>
                        <th>@lang('app.action')</th>
                        <th>@lang('app.article')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($audits as $audit)
                        <tr>
                            <td title="{{ $audit->created_at }}">{{ $audit->created_at->diffForHumans() }}</td>
                            <td>{{ optional($audit->user)->name }}</td>
                            <td class="fit">
                                @if($audit->event == 'created')
                                    <span class="text-success"><x-icon icon="star-of-life"/> {{ ucfirst($audit->event) }}</span>
                                @elseif($audit->event == 'updated')
                                    <span class="text-info"><x-icon icon="pencil-alt"/> {{ ucfirst($audit->event) }}</span>
                                @elseif($audit->event == 'deleted')
                                    <span class="text-danger"><x-icon icon="trash-alt"/> {{ ucfirst($audit->event) }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $mod = $audit->getModified();
                                    $title_mod = isset($mod['title']) ? $mod['title'] : null;
                                    $article = App\Models\Collaboration\WikiArticle::find($audit->auditable_id);
                                    $title = isset($title_mod) ? isset($title_mod['new']) ? $title_mod['new'] : $title_mod['old'] : ($article != null ? $article->title : '');
                                @endphp
                                @isset($article)
                                    <a href="{{ route('kb.articles.show', $article) }}">
                                        {{ $title }}
                                    </a>
                                @elseif($title)
                                    {{ $title }}
                                @else
                                    <em>@lang('app.not_available')</em>
                                @endisset
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $audits->links() }}
    @else
        <x-alert type="info">
            @lang('app.no_articles_found')
        </x-alert>
    @endif
@endsection
