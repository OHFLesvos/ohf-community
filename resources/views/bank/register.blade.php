@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open(['route' => 'bank.store']) !!}
    <div class="panel panel-primary">
        <div class="panel-heading">Register person</div>
        <div class="panel-body">
                <div class="form-group">
                    {{ Form::label('name') }}
                    {{ Form::text('name', null, [ 'class' => 'form-control', 'id' => 'name'  ]) }}
                </div>
                <div class="form-group">
                    {{ Form::label('family_name') }}
                    {{ Form::text('family_name', null, [ 'class' => 'form-control' ]) }}
                </div>
                <div class="form-group">
                    {{ Form::label('case_no') }}
                    {{ Form::number('case_no', null, [ 'class' => 'form-control' ]) }}
                </div>
                <div class="form-group">
                    {{ Form::label('nationality') }}
                    {{ Form::text('nationality', null, [ 'class' => 'form-control' ]) }}
                </div>
                <div class="form-group">
                    {{ Form::label('remarks') }}
                    {{ Form::text('remarks', null, [ 'class' => 'form-control' ]) }}
                </div>
                <div class="form-group">
                    {{ Form::label('value', 'Transaction') }}
                    {{ Form::number('value', null, [ 'class' => 'form-control', 'style' => 'width:80px' ]) }}
                </div>
        </div>
    </div>
    <p>
        {{ Form::submit('Add', [ 'name' => 'add', 'class' => 'btn btn-primary' ]) }} &nbsp;
        <a href="{{ route('bank.index') }}" class="btn btn-default">Cancel</a>
    </p>
    {!! Form::close() !!}
    
@endsection

@section('script')
    $(function(){
       $('#name').focus();
    });
@endsection
