@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    <div class="text-right">
        <a href="{{ route('bank.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to Overview</a> &nbsp;
    </div>
    <br>
    
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Transactions per day</div>
                <div class="panel-body">
                    <canvas id="chartTransactionsPerDay"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Transaction value per day</div>
                <div class="panel-body">
                    <canvas id="chartTransactionValuePerDay"></canvas>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('script')
    var ctx = document.getElementById("chartTransactionsPerDay").getContext('2d');
    var chartTransactionsPerDay = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["{!! implode('", "', array_keys($data['count'])) !!}"],
        datasets: [{
            label: 'Transactions',
            data: [{!! implode(', ', $data['count']) !!}],
            backgroundColor: "rgba(0,74,127,0.4)"
        }]
      }
    });
    var ctx = document.getElementById("chartTransactionValuePerDay").getContext('2d');
    var chartTransactionValuePerDay = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["{!! implode('", "', array_keys($data['sum'])) !!}"],
        datasets: [{
            label: 'SwissCross Drachma',
            data: [{!! implode(', ', $data['sum']) !!}],
            backgroundColor: "rgba(207,100,0,0.4)"
        }]
      }
    });
@endsection
