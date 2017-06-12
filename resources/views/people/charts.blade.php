@extends('layouts.app')

@section('title', 'People')

@section('content')

    <div class="text-right">
        <a href="{{ route('people.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to Overview</a> &nbsp;
    </div>
    <br>
    
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Nationalities</div>
                <div class="panel-body">
                    <canvas id="chartNationalities"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    
@endsection

@section('script')
    var ctx = document.getElementById("chartNationalities").getContext('2d');
    var chartNationalities = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ["{!! implode('", "', array_keys($data['nationalities'])) !!}"],
        datasets: [{
            label: 'Transactions',
            data: [{!! implode(', ', $data['nationalities']) !!}],
            backgroundColor: ["{!! implode('"," ', $colors) !!}"]
        }]
      }
    });
@endsection
