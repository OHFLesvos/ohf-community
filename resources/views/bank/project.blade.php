@extends('layouts.app')

@section('title', $project->name)

@section('content')

    <div id="app" class="my-3">
        <bar-chart title="Drachma deposited per day from {{ $project->name }}" 
            ylabel="Drachma"
            url="{{ route('bank.projectDepositStats', $project) }}"
            :legend=false
            :height=400></bar-chart>
    </div>

@endsection
