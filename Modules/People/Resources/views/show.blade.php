@extends('layouts.app')

@section('title', __('people::people.view_person'))  {{-- $person->family_name . ' ' . $person->name --}}

@section('content')

    @if(optional($person->helper)->isActive)
        @component('components.alert.info')
            @lang('people::people.person_registered_as_helper')
        @endcomponent
    @endif

    @isset($person->remarks)
        @component('components.alert.info')
            @lang('people::people.remarks'): {{ $person->remarks }}
        @endcomponent
    @endisset

    <div class="row mb-3">
        <div class="col-md">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-5">
                            <strong>
                                @lang('people::people.family_name')
                            </strong>
                        </div>
                        <div class="col-sm">
                            {{ $person->family_name }}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-5">
                            <strong>
                                @lang('people::people.name')
                            </strong>
                        </div>
                        <div class="col-sm">
                            {{ $person->name }}
                        </div>
                    </div>
                </li>
                @isset($person->gender)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people::people.gender')
                                </strong>
                            </div>
                            <div class="col-sm">
                                @if($person->gender == 'f')@icon(female) Female 
                                @elseif($person->gender == 'm')@icon(male) Male 
                                @endif
                            </div>
                        </div>
                    </li>
                @endisset
                @isset($person->date_of_birth)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people::people.date_of_birth')
                                </strong>
                            </div>
                            <div class="col-sm">
                                {{ $person->date_of_birth }}
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people::people.age')
                                </strong>
                            </div>
                            <div class="col-sm">
                                {{ $person->age }}
                            </div>
                        </div>
                    </li>
                @endisset
                @isset($person->police_no)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people::people.police_number')
                                </strong>
                            </div>
                            <div class="col-sm">
                                05/{{ $person->police_no }}
                            </div>
                        </div>
                    </li>
                @endisset
                @isset($person->case_no_hash)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people::people.case_number')
                                </strong>
                            </div>
                            <div class="col-sm">
                                @lang('app.yes')
                            </div>
                        </div>
                    </li>
                @endisset
                @isset($person->registration_no)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people::people.registration_number')
                                </strong>
                            </div>
                            <div class="col-sm">
                                {{ $person->registration_no }}
                            </div>
                        </div>
                    </li>
                @endisset
                @isset($person->section_card_no)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people::people.section_card_number')
                                </strong>
                            </div>
                            <div class="col-sm">
                                {{ $person->section_card_no }}
                            </div>
                        </div>
                    </li>
                @endisset
                @isset($person->nationality)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people::people.nationality')
                                </strong>
                            </div>
                            <div class="col-sm">
                                {{ $person->nationality }}
                            </div>
                        </div>
                    </li>
                @endisset
                @isset($person->languages)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people::people.languages')
                                </strong>
                            </div>
                            <div class="col-sm">
                                {!! is_array($person->languages) ? implode('<br>', $person->languages) : $person->languages !!}
                            </div>
                        </div>
                    </li>
                @endisset
                @isset($person->portrait_picture)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people::people.portrait_picture')
                                </strong>
                            </div>
                            <div class="col-sm">
                                <img src="{{ Storage::url($person->portrait_picture) }}" class="img-fluid">
                            </div>
                        </div>
                    </li>
                @endisset
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-5">
                            <strong>
                                @lang('app.created')
                            </strong>
                        </div>
                        <div class="col-sm">
                            {{ $person->created_at }}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-5">
                            <strong>
                                @lang('app.last_updated')
                            </strong>
                        </div>
                        <div class="col-sm">
                            {{ $person->updated_at }}
                        </div>
                    </div>
                </li>
            </ul>

            @if(isset($person->mother) || isset($person->father) || isset($person->partner) || count($person->children) > 0)
                <div class="card my-4">
                    <div class="card-header">@lang('people::people.relationships')
                        <a href="{{ route('people.relations', $person) }}" class="pull-right btn btn-sm btn-secondary">@icon(pencil) @lang('app.edit')</a>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @if(isset($person->mother))
                                <a href="{{ route('people.show', $person->mother) }}" class="list-group-item list-group-item-action">
                                    @include('people.person-label', ['person'=> $person->mother, 'prefix' => __('people::people.mother')])
                                </a>
                            @endif
                            @if(isset($person->father))
                                <a href="{{ route('people.show', $person->father) }}" class="list-group-item list-group-item-action">
                                    @include('people.person-label', ['person'=> $person->father, 'prefix' => __('people::people.father')])
                                </a>
                            @endif
                            @if(isset($person->partner))
                                <a href="{{ route('people.show', $person->partner) }}" class="list-group-item list-group-item-action">
                                    @include('people.person-label', ['person'=> $person->partner, 'prefix' => __('people::people.partner')])
                                </a>
                            @endif
                            @if(count($person->siblings) > 0)
                                @foreach($person->siblings->sortByDesc('age') as $sibling) 
                                    <a href="{{ route('people.show', $sibling) }}" class="list-group-item list-group-item-action">
                                        @include('people.person-label', ['person' => $sibling, 'prefix' => __('people::people.sibling')])
                                    </a>
                                @endforeach
                            @endif
                            @if(count($person->children) > 0)
                                @foreach($person->children->sortByDesc('age') as $child) 
                                    <a href="{{ route('people.show', $child) }}" class="list-group-item list-group-item-action">
                                        @include('people.person-label', ['person' => $child, 'prefix' => __('people::people.child')])
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

            @php
                $handouts = $person->couponHandouts()->orderBy('created_at', 'desc')->paginate(25);
            @endphp
            @if( ! $handouts->isEmpty() )
                <div class="card mb-4">
                    <div class="card-header">
                        @lang('people::people.coupons')
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
                @component('components.alert.info')
                    @lang('people::people.no_coupons_handed_out_so_far')
                @endcomponent
            @endif            

        </div>
    </div>

@endsection
