@extends('layouts.app')

@section('title', 'Reporting: ' . $article->project->name . ' - ' . $article->name)

@section('content')
    <div id="app" class="mb-3">
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
    </div>
@endsection

