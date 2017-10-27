@extends('layouts.app')

@section('title', 'Bank Charts')

@section('buttons')
    <a href="{{ route('bank.index') }}" class="btn btn-secondary"><i class="fa fa-times-circle"></i> Close</a>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Transactions per day</div>
                <div class="card-body">
                    <canvas id="chartTransactionsPerDay"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Transaction value per day</div>
                <div class="card-body">
                    <canvas id="chartTransactionValuePerDay"></canvas>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('script')
    var ctx = document.getElementById("chartTransactionsPerDay").getContext('2d');
    var chartTransactionsPerDay = new Chart(ctx, {
      type: 'bar',
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
      type: 'bar',
      data: {
        labels: ["{!! implode('", "', array_keys($data['sum'])) !!}"],
        datasets: [{
            label: 'Value',
            data: [{!! implode(', ', $data['sum']) !!}],
            backgroundColor: "rgba(207,100,0,0.4)"
        }]
      }
    });
@endsection
