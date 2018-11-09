@extends('layouts.app')

@section('title', __('shop.barber_shop'))

@section('content')
    @isset($list)
        @if(count($list) > 0)
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="fit text-right">#</th>
                            <th>@lang('people.person')</th>
                            <th class="fit"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $item)
                            @php
                                $person = $item['person'];
                            @endphp
                            <tr>
                                <td class="fit text-right align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">
                                    @if(optional($person->helper)->isActive)
                                        <strong class="text-warning">{{ strtoupper(__('people.helper')) }}</strong>
                                    @endif
                                    @can('view', $person)
                                        <a href="{{ route('people.show', $person) }}" target="_blank">
                                    @endcan
                                        @include('people.person-label', [ 'person' => $person ])
                                    @can('view', $person)
                                        </a>
                                    @endcan
                                </td>
                                <td class="fit align-middle">
                                    @if($item['redeemed'] != null)
                                        {{ $item['redeemed']->format('H:i') }}
                                    @else
                                        <button type="button" 
                                            class="btn btn-primary btn-sm checkin-button" 
                                            data-person-id="{{ $person->id }}"
                                            data-person-name="{{ $person->fullName }}">
                                                @icon(check)<span class="d-none d-sm-inline"> @lang('shop.check_in')</span>
                                        </button>
                                        <form class="d-inline delete-reservation-form" method="post" action="{{ route('shop.barber.removePerson') }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" 
                                                class="btn btn-sm btn-link text-danger"
                                                name="person_id" 
                                                value="{{ $person->id }}" 
                                                data-person-name="{{ $person->fullName }}" >
                                                @lang('app.remove')
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            @component('components.alert.warning')
                @lang('shop.no_persons_registered_today')
            @endcomponent
        @endif

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addHelperModal">
            @icon(plus-circle) @lang('people.add_helper')
        </button>
    @else
        @component('components.alert.warning')
            @lang('app.not_configured')
        @endcomponent
    @endisset
@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
    var checkinUrl = '{{ route('shop.barber.checkin') }}';
    var scannerDialogTitle = '@lang('people.qr_code_scanner')';
    var scannerDialogWaitMessage = '@lang('app.please_wait')';
    var checkInConfirmationMessage = '@lang('shop.confirm_checkin_of_person')';
    var delereReservationConfirmMessage = '@lang('shop.confirm_delete_reservation')';

    $('#addHelperModal').on('shown.bs.modal', function (e) {
        $('input[name="person_id_search"]').focus();
    });
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection

@section('content-footer')
    {!! Form::open(['route' => ['shop.barber.addPerson' ], 'method' => 'post']) !!}
        @component('components.modal', [ 'id' => 'addHelperModal' ])
            @slot('title', __('people.add_helper'))

            {{ Form::bsAutocomplete('person_id', null, route('people.helpers.filterPersons'), ['placeholder' => __('people.search_existing_person')], '') }}

            @slot('footer')
                {{ Form::bsSubmitButton(__('app.add')) }}
            @endslot
        @endcomponent
    {!! Form::close() !!}
@endsection
