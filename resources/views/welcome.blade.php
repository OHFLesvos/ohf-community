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

        @allowed('use-bank')
            <div class="card mb-4">
                <div class="card-header">
                    Bank
                    <a class="pull-right" href="{{ route('bank.index')  }}">Show bank</a>
                </div>
                <div class="card-body">
                    <p>Served <a href="{{ route('bank.index') }}?q=today:"><strong>{{ $num_people_served_today }}</strong></a> persons and handed out <strong>{{ $transaction_value_today }}</strong> drachma today.</p>
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



    </div>

@endsection
