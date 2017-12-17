@extends('layouts.app')

@section('title', 'Kitchen')

@section('content')

    {!! Form::open(['route' => ['kitchen.storeIncomming']]) !!}
    <div class="card mb-4">
        <div class="card-header">Register incomming article</div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-sm">
                    {{ Form::bsText('name', null, [ 'placeholder' => 'Article', 'required', 'autofocus', 'autocomplete' => 'off' ], '') }}
                </div>
                <div class="col-sm-3">
                    {{ Form::bsNumber('value', null, [ 'placeholder' => 'Amount', 'required' ], '') }}
                </div>
                <div class="col-sm-3">
                    {{ Form::bsDate('date', \Carbon\Carbon::now()->toDateString(), [ 'required', 'max' => \Carbon\Carbon::now()->toDateString() ], '') }}
                </div>
                <div class="col-sm-auto">
                    {{ Form::bsSubmitButton('Add', 'plus-circle') }}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    @if( ! $articles->isEmpty() )
        <table class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr class="d-none d-sm-table-row">
                    <th>Article</th>
                    @foreach(range(6, 0) as $i)
                        <th>{{ Carbon\Carbon::today()->subDays($i)->toDateString() }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @foreach ($articles as $article)
                <tr>
                    <td>{{ $article->name }}</td>
                    @foreach(range(6, 0) as $i)
                        <td class="d-block d-sm-table-cell">
                            <span class="d-inline d-sm-none text-muted">{{ Carbon\Carbon::today()->subDays($i)->toDateString() }}: </span>
                            {{ $article->dayTransactions(Carbon\Carbon::today()->subDays($i)) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

@endsection

@section('script')
    $(function(){
        $('input[name="name"]').typeahead({
            source: [ @foreach($articleNames as $article) '{!! $article !!}', @endforeach ]
        });
    });
@endsection