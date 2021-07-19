@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Knowledge Base'))
@section('site-title', __('Latest changes') . ' - ' . __('Knowledge Base'))

@section('content')
    <h1 class="display-4">{{ __('Latest changes') }}</h1>
    @if(! $audits->isEmpty())
        <div class="table-responsive">
            <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Author') }}</th>
                        <th>{{ __('Action') }}</th>
                        <th>{{ __('Article') }}</th>
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
                                    <em>{{ __('not available') }}</em>
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
            {{ __('No articles found.') }}
        </x-alert>
    @endif
@endsection
