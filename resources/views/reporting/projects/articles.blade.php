@extends('layouts.app')

@section('title', 'Reporting: ' . $projectName . ' Articles')

@section('content')
    <div id="app" class="mb-3">
        <ul class="nav nav-tabs tab-remember" id="articlesTabNav" role="tablist">
            @foreach($types as $type)
            <li class="nav-item">
                <a class="nav-link" id="{{ $type }}-tab" data-toggle="tab" href="#{{ $type }}" role="tab" aria-controls="{{ $type }}" aria-selected="true">{{ ucfirst($type) }}</a>
            </li>
            @endforeach
        </ul>
        <div class="tab-content p-3" id="articlesTabContent">
            @foreach($types as $type)
                <div class="tab-pane fade" id="{{ $type }}" role="tabpanel" aria-labelledby="{{ $type }}-tab">
                    @if( ! $data[$type]->isEmpty() )
                        @foreach($data[$type] as $article)
                            <h3 class="display-4">{{ $article->name }}</h3>
                            <div class="row">
                                <div class="col-md">
                                    <bar-chart
                                        title="{{ $article->name }} ({{ $article->type }}) per day"
                                        ylabel="{{ $article->unit }}"
                                        url="{{ route('reporting.articles.transactionsPerDay', $article) }}" 
                                        :height=300
                                        :legend=false
                                        class="mb-4">
                                    </bar-chart>
                                </div>
                                <div class="col-md">
                                    <bar-chart
                                        title="{{ $article->name }} ({{ $article->type }}) per week"
                                        ylabel="{{ $article->unit }}"
                                        url="{{ route('reporting.articles.transactionsPerWeek', $article) }}" 
                                        :height=300
                                        :legend=false
                                        class="mb-4">
                                    </bar-chart>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md">
                                        <bar-chart
                                            title="{{ $article->name }} ({{ $article->type }}) per month"
                                            ylabel="{{ $article->unit }}"
                                            url="{{ route('reporting.articles.transactionsPerMonth', $article) }}" 
                                            :height=300
                                            :legend=false
                                            class="mb-4">
                                        </bar-chart>
                                    </div>
                                <div class="col-md">
                                    <bar-chart
                                        title="Average {{ $article->name }} ({{ $article->type }}) per weekday"
                                        ylabel="{{ $article->unit }}"
                                        url="{{ route('reporting.articles.avgTransactionsPerWeekDay', $article) }}"
                                        :height=300
                                        :legend=false
                                        class="mb-2">
                                    </bar-chart>
                                </div>
                            </div>
                        @endforeach
                    @else
                        @component('components.alert.info')
                            No articels found.
                        @endcomponent
                    @endif                    
                </div>
            @endforeach
        </div>
    </div>
@endsection

