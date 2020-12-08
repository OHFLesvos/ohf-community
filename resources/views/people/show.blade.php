@extends('layouts.app', ['wide_layout' => false])

@section('title', __('people.view_person'))

@section('content')

    {{-- Community volunteer --}}
    @if($person->linkedCommunityVolunteer() !== null)
        <x-alert type="info">
            @lang('people.person_registered_as_community_volunteer')
        </x-alert>
    @endif

    {{-- Remarks --}}
    @isset($person->remarks)
        <x-alert type="info">
            @lang('people.remarks'): {{ $person->remarks }}
        </x-alert>
    @endisset

    <div class="row mb-3">
        {{-- Properties --}}
        <div class="col-md">
            <div class="card shadow-sm mb-4">
                <div class="card-header">@lang('app.data')</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people.name')
                                </strong>
                            </div>
                            <div class="col-sm">
                                {{ $person->name }}
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong>
                                    @lang('people.family_name')
                                </strong>
                            </div>
                            <div class="col-sm">
                                {{ $person->family_name }}
                            </div>
                        </div>
                    </li>
                    @isset($person->gender)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm-5">
                                    <strong>
                                        @lang('people.gender')
                                    </strong>
                                </div>
                                <div class="col-sm">
                                    <x-icon-gender :gender="$person->gender" with-label/>
                                </div>
                            </div>
                        </li>
                    @endisset
                    @isset($person->date_of_birth)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm-5">
                                    <strong>
                                        @lang('people.date_of_birth')
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
                                        @lang('people.age')
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
                                        @lang('people.police_number')
                                    </strong>
                                </div>
                                <div class="col-sm">
                                    {{ $person->police_no_formatted }}
                                </div>
                            </div>
                        </li>
                    @endisset
                    @isset($person->nationality)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm-5">
                                    <strong>
                                        @lang('people.nationality')
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
                                        @lang('people.languages')
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
                                        @lang('people.portrait_picture')
                                    </strong>
                                </div>
                                <div class="col-sm">
                                    <img src="{{ Storage::url($person->portrait_picture) }}" class="img-fluid img-thumbnail" alt="Portrait">
                                </div>
                            </div>
                        </li>
                    @endisset
                </ul>
            </div>
        </div>
        <div class="col-md">
            {{-- Cards --}}
            <div class="card shadow-sm">
                <div class="card-header">@lang('app.cards')</div>
                @isset($person->card_no)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th class="fit">@lang('app.date')</th>
                                    <th>@lang('app.card_number')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fit">@isset($person->card_issued) {{ $person->card_issued }} <small>{{ $person->card_issued->diffForHumans() }}@endisset</small></td>
                                    <td><strong>{{ substr($person->card_no, 0, 7) }}</strong>{{ substr($person->card_no, 7) }}</td>
                                </tr>
                                @foreach ($person->revokedCards as $card)
                                    <tr>
                                        <td class="fit"><span class="text-danger">@lang('app.revoked')</span> {{ $card->created_at }} <small>{{  $card->created_at->diffForHumans() }}</small></td>
                                        <td><del><strong>{{ substr($card->card_no, 0, 7) }}</strong>{{ substr($card->card_no, 7) }}</del></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-alert type="info" class="m-0">
                        @lang('app.no_cards_registered')
                    </x-alert>
                @endisset
            </div>
        </div>
    </div>

    <p class="text-right">
        <small>@lang('app.created'): {{ $person->created_at->diffForHumans() }} <small class="text-muted">{{ $person->created_at }}</small></small><br>
        <small>@lang('app.last_updated'): {{ $person->updated_at->diffForHumans() }} <small class="text-muted">{{ $person->updated_at }}</small></small></small>
    </p>
@endsection
