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
            @include('people::snippets.properties')

            @if(isset($person->mother) || isset($person->father) || isset($person->partner) || count($person->children) > 0)
                @include('people::snippets.relationships', ['showRouteName' => 'bank.people.show'])
            @endif

        </div>
        <div class="col-md">

            @isset($person->card_no))
                @include('people::snippets.card')
            @endisset

            @php
                $handouts = $person->couponHandouts()->orderBy('created_at', 'desc')->paginate(25);
            @endphp
            @if( ! $handouts->isEmpty() )
                <div class="card mb-4">
                    <div class="card-header">
                        @lang('bank::coupons.coupons')
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
                    @lang('bank::coupons.no_coupons_handed_out_so_far')
                @endcomponent
            @endif            

        </div>
    </div>

@endsection
