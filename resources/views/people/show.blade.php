@extends('layouts.app')

@section('title', __('people.view_person'))  {{-- $person->family_name . ' ' . $person->name --}}

@section('content')

    <div class="row">
        <div class="col-md">

            {{-- <div class="card mb-4">
                <div class="card-header">Personal data</div>
                <div class="card-body p-0"> --}}

                    <table class="table m-0">
                        <tbody>
                            <tr>
                                <th>@lang('people.family_name')</th>
                                <td>{{ $person->family_name }}</td>
                            </tr>
                            <tr>
                                <th>@lang('people.name')</th>
                                <td>{{ $person->name }}</td>
                            </tr>
                            @if(isset($person->gender))
                                <tr>
                                    <th>@lang('people.gender')</th>
                                    <td>
                                        @if($person->gender == 'f')@icon(female) Female 
                                        @elseif($person->gender == 'm')@icon(male) Male 
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            @if(isset($person->date_of_birth))
                                <tr>
                                    <th>@lang('people.date_of_birth')</th>
                                    <td>{{ $person->date_of_birth }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('people.age')</th>
                                    <td>{{ $person->age }}</td>
                                </tr>
                            @endif
                            @if(isset($person->police_no))
                                <tr>
                                    <th>@lang('people.police_number')</th>
                                    <td>{{ $person->police_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->case_no))
                                <tr>
                                    <th>@lang('people.case_number')</th>
                                    <td>{{ $person->case_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->medical_no))
                                <tr>
                                    <th>@lang('people.medical_number')</th>
                                    <td>{{ $person->medical_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->registration_no))
                                <tr>
                                    <th>@lang('people.registration_number')</th>
                                    <td>{{ $person->registration_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->section_card_no))
                                <tr>
                                    <th>@lang('people.section_card_number')</th>
                                    <td>{{ $person->section_card_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->temp_no))
                                <tr>
                                    <th>@lang('people.temporary_number')</th>
                                    <td>{{ $person->temp_no }}</td>
                                </tr>
                            @endif
                            @if(isset($person->nationality))
                            <tr>
                                <th>@lang('people.nationality')</th>
                                <td>{{ $person->nationality }}</td>
                            </tr>
                            @endif
                            @if(isset($person->languages))
                                <tr>
                                    <th>@lang('people.languages')</th>
                                    <td>{{ $person->languages }}</td>
                                </tr>
                            @endif
                            @if(isset($person->skills))
                                <tr>
                                    <th>@lang('people.skills')</th>
                                    <td>{{ $person->skills }}</td>
                                </tr>
                            @endif
                            @if(isset($person->remarks))
                                <tr>
                                    <th>@lang('people.remarks')</th>
                                    <td>{{ $person->remarks }}</td>
                                </tr>
                            @endif
                             <tr>
                                <th>@lang('app.created')</th>
                                <td>{{ $person->created_at }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.last_updated')</th>
                                <td>{{ $person->updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>

                {{-- </div>
            </div> --}}

            @if(isset($person->mother) || isset($person->father) || isset($person->partner) || count($person->children) > 0)
                <div class="card mb-4">
                    <div class="card-header">@lang('people.relationships')
                        <a href="{{ route('people.relations', $person) }}" class="pull-right btn btn-sm btn-secondary">@icon(pencil) Edit</a>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @if(isset($person->mother))
                                <a href="{{ route('people.show', $person->mother) }}" class="list-group-item list-group-item-action">
                                    @include('people.person-label', ['person'=> $person->mother, 'prefix' => 'Mother'])
                                </a>
                            @endif
                            @if(isset($person->father))
                                <a href="{{ route('people.show', $person->father) }}" class="list-group-item list-group-item-action">
                                    @include('people.person-label', ['person'=> $person->father, 'prefix' => 'Father'])
                                </a>
                            @endif
                            @if(isset($person->partner))
                                <a href="{{ route('people.show', $person->partner) }}" class="list-group-item list-group-item-action">
                                    @include('people.person-label', ['person'=> $person->partner, 'prefix' => 'Partner'])
                                </a>
                            @endif
                            @if(count($person->children) > 0)
                                @foreach($person->children->sortByDesc('age') as $child) 
                                    <a href="{{ route('people.show', $child) }}" class="list-group-item list-group-item-action">
                                        @include('people.person-label', ['person' => $child, 'prefix' => 'Child'])
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="col-md">

            @if($person->worker)
                <div class="alert alert-info">
                    @icon(info-circle) @lang('people.person_registered_as_helper')
                </div>            
            @endif

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

            @php
                $handouts = $person->couponHandouts()->orderBy('created_at', 'desc')->paginate(25);
            @endphp
            @if( ! $handouts->isEmpty() )
                <div class="card mb-4">
                    <div class="card-header">
                        @lang('people.coupons')
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>@lang('app.date')</th>
                                    <th>@lang('app.type')</th>
                                    <th>@lang('app.registered')</th>
                                    <th>@lang('app.author')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($handouts as $handout)
                                    <tr>
                                        <td>{{ $handout->date }}</td>
                                        <td>{{ $handout->couponType->daily_amount }} {{ $handout->couponType->name }}</td>
                                        <td>{{ (new Carbon\Carbon($handout->created_at))->diffForHumans() }} <small class="text-muted">{{ $handout->created_at }}</small></td>
                                        <td>
                                            @if(isset($handout->user))
                                                {{ $handout->user->name }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $handouts->links() }}
            @else
                <div class="alert alert-info m-0">
                    @lang('people.no_coupons_handed_out_so_far')
                </div>
            @endif            

        </div>
    </div>

@endsection
