@extends('layouts.app')

@section('title', 'View Person')

@section('buttons')
    <a href="{{ route('people.edit', $person) }}" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</a>
    @can('delete', $person)
        <form method="POST" action="{{ route('people.destroy', $person) }}" class="d-inline">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            {{ Form::button('<i class="fa fa-trash"></i> Delete', [ 'type' => 'submit', 'class' => 'btn btn-danger', 'id' => 'delete_button' ]) }}
        </form>
    @endcan
    <a href="{{ route( $closeRoute ) }}" class="btn btn-secondary"><i class="fa fa-times-circle"></i> Close</a>
@endsection

@section('content')

    <div class="row">
        <div class="col-md">

            <div class="card mb-4">
                <div class="card-header">Personal data</div>
                <div class="card-body p-0">

                    <table class="table m-0">
                        <tbody>
                            <tr>
                                <th>Name:</th>
                                <td>{{ $person->name }}</td>
                            </tr>
                            <tr>
                                <th>Family Name:</th>
                                <td>{{ $person->family_name }}</td>
                            </tr>
                            <tr>
                                <th>Case No:</th>
                                <td>{{ $person->case_no }}</td>
                            </tr>
                            <tr>
                                <th>Nationality:</th>
                                <td>{{ $person->nationality }}</td>
                            </tr>
                            <tr>
                                <th>Languages:</th>
                                <td>{{ $person->languages }}</td>
                            </tr>
                            <tr>
                                <th>Skills:</th>
                                <td>{{ $person->skills }}</td>
                            </tr>
                            <tr>
                                <th>Remarks:</th>
                                <td>{{ $person->remarks }}</td>
                            </tr>
                            <tr>
                                <th>Registered:</th>
                                <td>{{ $person->created_at }}</td>
                            </tr>
                            <tr>
                                <th>Last updated:</th>
                                <td>{{ $person->updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
        <div class="col-md">

            <div class="card mb-4">
                <div class="card-header">Transactions</div>
                <div class="card-body @if( ! $transactions->isEmpty() )p-0 @endif">

                    @if( ! $transactions->isEmpty() )
                        <table class="table table-sm table-hover m-0">
                            <thead>
                                <tr>
                                    <th style="width: 200px">Date</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at }}</td>
                                        <td>{{ $transaction->value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info m-0">
                            No transactions found.
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
    $(function(){
        $('#delete_button').on('click', function(){
            return confirm('Really delete this person?');
        });
    });
@endsection