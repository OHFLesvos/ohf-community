@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1 class="display-4">Hello {{ Auth::user()->name }}!</h1>
    <p class="lead">Welcome to the {{ Config::get('app.product_name') }}.</p>

    <div class="card-columns">

        @can('list', App\Person::class)
            <div class="card mb-4">
                <div class="card-header">
                    People
                    <a class="pull-right" href="{{ route('people.index')  }}">Manage</a>
                </div>
                <div class="card-body">
                    <p>There are <strong>{{ $num_people }}</strong> people registered in our database. @if($num_people_added_today > 0)(<strong>{{ $num_people_added_today }}</strong> new today)@endif</p>
                </div>
            </div>
        @endcan

        @allowed(['do-bank-withdrawals', 'view-bank-reports'])
            <div class="card mb-4">
                <div class="card-header">
                    Bank
                    @allowed('do-bank-withdrawals')
                        <a class="pull-right" href="{{ route('bank.index')  }}">Go to bank</a>
                    @else
                        <a class="pull-right" href="{{ route('reporting.bank.withdrawals')  }}">View bank report</a>
                    @endallowed
                </div>
                <div class="card-body">
                    <p>Served 
                        @allowed('do-bank-withdrawals')
                            <a href="{{ route('bank.withdrawalTransactions') }}"><strong>{{ $num_people_served_today }}</strong></a> 
                        @else
                            <strong>{{ $num_people_served_today }}</strong>
                        @endallowed
                    persons and handed out <strong>{{ $transaction_value_today }}</strong> drachma today.</p>
                </div>
            </div>
        @endallowed

        @can('list', App\User::class)
            <div class="card mb-4">
                <div class="card-header">
                    Users
                    <a class="pull-right" href="{{ route('users.index')  }}">Manage</a>
                </div>
                <div class="card-body">
                    <p>There are <strong>{{ $num_users }}</strong> users in our database. The newest user is <a href="{{ route('users.show', $latest_user) }}">{{ $latest_user->name }}</a>.</p>
                </div>
            </div>
        @endcan

        @allowed('view-reports')
            <div class="card mb-4">
                <div class="card-header">
                    Reports
                    <a class="pull-right" href="{{ route('reporting.index')  }}">More reports</a>
                </div>
                <div class="card-body">
                    @foreach(Config::get('reporting.reports') as $report)
                        @if($report['featured'])
                            @allowed($report['gate'])
                                <a href="{{ route($report['route']) }}">{{ $report['name'] }}</a><br>
                            @endallowed
                        @endif
                    @endforeach                    
                </div>
            </div>
        @endallowed

    </div>

@endsection
