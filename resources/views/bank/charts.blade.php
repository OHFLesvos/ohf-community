@extends('layouts.app')

@section('title', 'Bank Charts')

@section('content')

    <div id="app">
        <div class="card mb-4">
            <div class="card-header">Number of Transactions</div>
            <div class="card-body">
                <bar-chart
                    title="Transactions per day"
                    ylabel="Transactions"
                    url="{{ route('bank.numTransactions') }}" 
                    :height=300
                    :legend=false>
                </bar-chart>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Transaction value</div>
            <div class="card-body">
                <bar-chart
                    title="Transaction value per day"
                    ylabel="Value"
                    url="{{ route('bank.sumTransactions') }}"
                    :height=300
                    :legend=false>
                </bar-chart>
            </div>
        </div>
    </div>

@endsection
