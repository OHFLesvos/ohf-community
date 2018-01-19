@extends('layouts.app')

@section('title', $article->name . ' (' . $article->type . ')')

@section('content')

    <div id="app" class="mb-3">
        <bar-chart
            title="{{ $article->name }} ({{ $article->type }}) per day"
            ylabel="{{ $article->unit }}"
            url="{{ route('reporting.articles.transactionsPerDay', $article) }}" 
            :height=300
            :legend=false
            class="mb-4">
        </bar-chart>
        <bar-chart
            title="Average {{ $article->name }} ({{ $article->type }}) per weekday"
            ylabel="{{ $article->unit }}"
            url="{{ route('reporting.articles.avgTransactionsPerWeekDay', $article) }}"
            :height=300
            :legend=false
            class="mb-2">
        </bar-chart>
    </div>

@endsection

