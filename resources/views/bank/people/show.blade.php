@extends('layouts.app')

@section('title', __('people.view_person'))

@section('content')

    @if(optional($person->helper)->isActive)
        @component('components.alert.info')
            @lang('people.person_registered_as_helper')
        @endcomponent
    @endif

    @isset($person->remarks)
        @component('components.alert.info')
            @lang('people.remarks'): {{ $person->remarks }}
        @endcomponent
    @endisset

    <div class="row mb-3">
        <div class="col-md">
            {{-- Properties --}}
            @include('people.snippets.properties', [ 'showRouteName' => 'bank.people.show' ])
        </div>
        <div class="col-md">

            {{-- Card --}}
            @include('people.snippets.card')

            {{-- Coupons --}}
            <h4>@lang('coupons.coupons')</h4>
            @php
                $showHandoutLimit = 15;
                $handouts = $person->couponHandouts()->orderBy('created_at', 'desc')->limit($showHandoutLimit)->get();
            @endphp
            @if( ! $handouts->isEmpty() )
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>@lang('app.date')</th>
                                <th>@lang('app.type')</th>
                                <th>@lang('app.registered')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($handouts as $handout)
                                <tr>
                                    <td>{{ $handout->date }}</td>
                                    <td>{{ $handout->couponType->daily_amount }} {{ $handout->couponType->name }}</td>
                                    <td>{{ $handout->created_at->diffForHumans() }} <small class="text-muted">{{ $handout->created_at }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p><small>
                    @php
                        $numHandouts = $person->couponHandouts()->count();
                        $firstHandout = $person->couponHandouts()->orderBy('created_at', 'asc')->first();
                    @endphp
                    @if ($showHandoutLimit < $numHandouts)
                        @lang('app.last_n_transactions_shown', [ 'num' => $showHandoutLimit ])
                    @endif
                    @lang('coupons.n_coupons_received_total_since_date', [ 'num' => $numHandouts, 'date' => $firstHandout->created_at->toDateString(), 'date_diff' => $firstHandout->created_at->diffForHumans() ])
                </small></p>
            @else
                @component('components.alert.info')
                    @lang('coupons.no_coupons_received_so_far')
                @endcomponent
            @endif

        </div>
    </div>

    <hr>
    <p class="text-right">
        <small>@lang('app.created'): {{ $person->created_at->diffForHumans() }} <small class="text-muted">{{ $person->created_at }}</small></small><br>
        <small>@lang('app.last_updated'): {{ $person->updated_at->diffForHumans() }} <small class="text-muted">{{ $person->updated_at }}</small></small></small>
    </p>

@endsection
