@extends('layouts.app')

@section('title', 'Bank Charts')

@section('content')

    <div id="app">
        <bar-chart
            title="Transactions per day"
            ylabel="Transactions"
            url="{{ route('bank.numTransactions') }}" 
            :height=300
            :legend=false
            class="mb-4">
        </bar-chart>
        <bar-chart
            title="Transaction value per day"
            ylabel="Value"
            url="{{ route('bank.sumTransactions') }}"
            :height=300
            :legend=false
            class="mb-2">
        </bar-chart>
    </div>

@endsection
