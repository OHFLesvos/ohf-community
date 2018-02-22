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
                    <a class="float-right" href="{{ route('people.index')  }}">Manage</a>
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
                        <a class="float-right" href="{{ route('bank.index')  }}">Go to bank</a>
                    @else
                        <a class="float-right" href="{{ route('reporting.bank.withdrawals')  }}">View bank report</a>
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
                    <a class="float-right" href="{{ route('users.index')  }}">Manage</a>
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
                    <a class="float-right" href="{{ route('reporting.index')  }}">More reports</a>
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

        @if (sizeof($other) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    Other tools
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($other as $o)
                            <a href="{{ route($o['route']) }}" class="list-group-item list-group-item-action">@icon({{ $o['icon'] }}) {{ $o['name'] }}</a>
                        @endforeach                    
                    </div>
                </div>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                Changelog
                <span class="float-right">Version: <strong>{{ $app_version }}</strong></span>
            </div>
            <div class="card-body">
                <p>Read about the latest changes of this application <a href="{{ route('changelog') }}">here</a>.</p>
            </div>
        </div>

    </div>

@endsection
