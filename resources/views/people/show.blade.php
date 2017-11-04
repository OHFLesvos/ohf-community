@extends('layouts.app')

@section('title', 'View Person')

@section('buttons')
    @can('update', $person)
        <a href="{{ route('people.edit', $person) }}" class="btn btn-primary d-none d-md-inline-block"><i class="fa fa-pencil"></i><span class="d-none d-md-inline-block"> Edit</span></a>
    @endcan
    @can('delete', $person)
        <form method="POST" action="{{ route('people.destroy', $person) }}" class="d-inline">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            {{ Form::button('<i class="fa fa-trash"></i> Delete</span>', [ 'type' => 'submit', 'class' => 'btn btn-danger d-none d-md-inline-block delete-confirmation', 'data-confirmation' => 'Really delete this person?' ]) }}
            {{ Form::button('<i class="fa fa-trash"></i>', [ 'type' => 'submit', 'class' => 'btn btn-link text-light d-md-none delete-confirmation', 'data-confirmation' => 'Really delete this person?' ]) }}
        </form>
    @endcan
    <a href="{{ route( $closeRoute ) }}" class="btn btn-secondary d-none d-md-inline-block">@icon(times-circle) Close</a>
@endsection

@section('backLink', route( $closeRoute ))

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
                                <th>Case Number:</th>
                                <td>{{ $person->case_no }}</td>
                            </tr>
                            <tr>
                                <th>Medical Number:</th>
                                <td>{{ $person->medical_no }}</td>
                            </tr>
                            <tr>
                                <th>Registration Number:</th>
                                <td>{{ $person->registration_no }}</td>
                            </tr>
                            <tr>
                                <th>Section Card Number:</th>
                                <td>{{ $person->section_card_no }}</td>
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
                <div class="card-header">
                    Transactions
                    @if ( $transactions->perPage() < $transactions->total() )
                        <small class="pull-right text-muted">showing latest {{ $transactions->perPage() }} transactions</small>
                    @endif
                </div>
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

    @can('update', $person)
        @include('components.action-button', [ 'route' => route('people.edit', $person), 'icon' => 'pencil' ])
    @endcan

@endsection
