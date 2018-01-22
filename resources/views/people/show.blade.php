@extends('layouts.app')

@section('title', 'View Person')  {{-- $person->family_name . ' ' . $person->name --}}

@section('content')

    <div class="row">
        <div class="col-md">

            <div class="card mb-4">
                <div class="card-header">Personal data</div>
                <div class="card-body p-0">

                    <table class="table m-0">
                        <tbody>
                            <tr>
                                <th>Family Name:</th>
                                <td>{{ $person->family_name }}</td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td>{{ $person->name }}</td>
                            </tr>
                            @if(isset($person->gender))
                                <tr>
                                    <th>Gender:</th>
                                    <td>
                                        @if($person->gender == 'f')@icon(female) Female 
                                        @elseif($person->gender == 'm')@icon(male) Male 
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            @if(isset($person->date_of_birth))
                                <tr>
                                    <th>Date of Birth:</th>
                                    <td>{{ $person->date_of_birth }}</td>
                                </tr>
                                <tr>
                                    <th>Age:</th>
                                    <td>{{ $person->age }}</td>
                                </tr>
                            @endif
                            @if(isset($person->police_no))
                                <tr>
                                    <th>Police Number:</th>
                                    <td>{{ $person->police_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->case_no))
                                <tr>
                                    <th>Case Number:</th>
                                    <td>{{ $person->case_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->medical_no))
                                <tr>
                                    <th>Medical Number:</th>
                                    <td>{{ $person->medical_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->registration_no))
                                <tr>
                                    <th>Registration Number:</th>
                                    <td>{{ $person->registration_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->section_card_no))
                                <tr>
                                    <th>Section Card Number:</th>
                                    <td>{{ $person->section_card_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->temp_no))
                                <tr>
                                    <th>Temporary Number:</th>
                                    <td>{{ $person->temp_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->nationality))
                            <tr>
                                <th>Nationality:</th>
                                <td>{{ $person->nationality }}</td>
                            </tr>
                            @endif
                            @if(isset($person->languages))
                                <tr>
                                    <th>Languages:</th>
                                    <td>{{ $person->languages }}</td>
                                </tr>
                            @endif
                            @if(isset($person->skills))
                                <tr>
                                    <th>Skills:</th>
                                    <td>{{ $person->skills }}</td>
                                </tr>
                            @endif
                            @if(isset($person->remarks))
                                <tr>
                                    <th>Remarks:</th>
                                    <td>{{ $person->remarks }}</td>
                                </tr>
                            @endif
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

            @if(isset($person->mother) || isset($person->father) || isset($person->partner) || count($person->children) > 0)
                <div class="card mb-4">
                    <div class="card-header">Relationships</div>
                    <div class="card-body">
                        <div class="list-group">
                            @if(isset($person->mother))
                                <a href="{{ route('people.show', $person->mother) }}" class="list-group-item list-group-item-action">
                                    Mother:
                                    @if($person->mother->gender == 'f')@icon(female) 
                                    @elseif($person->mother->gender == 'm')@icon(male) 
                                    @endif
                                    {{ $person->mother->family_name }} {{ $person->mother->name }}}@if(isset($person->mother->date_of_birth)), 
                                        {{ $person->mother->date_of_birth }} (age {{ $person->mother->age }})
                                    @endif
                                </a>
                            @endif
                            @if(isset($person->father))
                                <a href="{{ route('people.show', $person->father) }}" class="list-group-item list-group-item-action">
                                    Father:
                                    @if($person->father->gender == 'f')@icon(female) 
                                    @elseif($person->father->gender == 'm')@icon(male) 
                                    @endif
                                    {{ $person->father->family_name }} {{ $person->father->name }}}@if(isset($person->father->date_of_birth)), 
                                        {{ $person->father->date_of_birth }} (age {{ $person->father->age }})
                                    @endif
                                </a>
                            @endif
                            @if(isset($person->partner))
                                <a href="{{ route('people.show', $person->partner) }}" class="list-group-item list-group-item-action">
                                    Partner:
                                    @if($person->partner->gender == 'f')@icon(female) 
                                    @elseif($person->partner->gender == 'm')@icon(male) 
                                    @endif
                                    {{ $person->partner->family_name }} {{ $person->partner->name }}@if(isset($person->partner->date_of_birth)), 
                                        {{ $person->partner->date_of_birth }} (age {{ $person->partner->age }})
                                    @endif
                                </a>
                            @endif
                            @if(count($person->children) > 0)
                                @foreach($person->children->sortByDesc('age') as $child) 
                                    <a href="{{ route('people.show', $child) }}" class="list-group-item list-group-item-action">
                                        Child: 
                                        @if($child->gender == 'f')@icon(female) 
                                        @elseif($child->gender == 'm')@icon(male) 
                                        @endif
                                        {{ $child->family_name }} {{ $child->name }}@if(isset($child->date_of_birth)), 
                                            {{ $child->date_of_birth }} (age {{ $child->age }})
                                        @endif
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="col-md">

            @if(isset($person->card_no))
                <div class="card mb-4">
                    <div class="card-header">Card</div>
                    <div class="card-body">
                        <strong>{{ substr($person->card_no, 0, 7) }}</strong>{{ substr($person->card_no, 7) }} issued on <strong>{{ $person->card_issued }}</strong>
                        @if(count($person->revokedCards) > 0)
                            <br><br><p>Revoked cards:</p>
                            <table class="table table-sm table-hover m-0">
                                <thead>
                                    <tr>
                                        <th style="width: 200px">Date</th>
                                        <th>Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($person->revokedCards as $card)
                                        <tr>
                                            <td>{{ $card->created_at }}</td>
                                            <td><strong>{{ substr($card->card_no, 0, 7) }}</strong>{{ substr($card->card_no, 7) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    Transactions
                </div>
                <div class="card-body">
                    @if( ! $transactions->isEmpty() )
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Value</th>
                                    <th>Author</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->diffForHumans() }} <small class="text-muted">{{ $transaction->created_at }}</small></td>
                                        <td>{{ $transaction->value }}</td>
                                        <td>
                                            @if(isset($transaction->user))
                                                {{ $transaction->user->name }}
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $transactions->links('vendor.pagination.bootstrap-4') }}
                    @else
                        <div class="alert alert-info m-0">
                            No transactions found.
                        </div>
                    @endif

                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    Boutique
                </div>
                <div class="card-body">

                    @if( $person->boutique_coupon != null )
                        Last coupon handed out on {{ $person->boutique_coupon }} ({{ (new Carbon\Carbon($person->boutique_coupon))->diffForHumans() }}).
                    @else
                        <div class="alert alert-info m-0">
                            No boutique coupon handed out so far.
                        </div>
                    @endif

                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    Diapers
                </div>
                <div class="card-body">

                    @if( $person->diapers_coupon != null )
                        Last coupon handed out on {{ $person->diapers_coupon }} ({{ (new Carbon\Carbon($person->diapers_coupon))->diffForHumans() }}).
                    @else
                        <div class="alert alert-info m-0">
                            No diapers coupon handed out so far.
                        </div>
                    @endif

                </div>
            </div>
            
        </div>
    </div>

@endsection
