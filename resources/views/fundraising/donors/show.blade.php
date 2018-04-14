@extends('layouts.app')

@section('title', __('fundraising.show_donor'))

@section('content')

    <div class="row">

        <div class="col-md mb-4">

            <ul class="list-group list-group-flush">

                @if($donor->first_name != null || $donor->last_name != null)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('app.name')</strong></div>
                            <div class="col-sm">{{ $donor->first_name }} {{ $donor->last_name }}</div>
                        </div>
                    </li>
                @endisset

                @isset($donor->company)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('fundraising.company')</strong></div>
                            <div class="col-sm">{{ $donor->company }}</div>
                        </div>
                    </li>
                @endisset

                @isset($donor->street)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('fundraising.street')</strong></div>
                            <div class="col-sm">{{ $donor->street }}</div>
                        </div>
                    </li>
                @endisset

                @isset($donor->zip)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('fundraising.zip')</strong></div>
                            <div class="col-sm">{{ $donor->zip }}</div>
                        </div>
                    </li>
                @endisset

                @isset($donor->city)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('fundraising.city')</strong></div>
                            <div class="col-sm">{{ $donor->city }}</div>
                        </div>
                    </li>
                @endisset

                @isset($donor->country_name)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('fundraising.country')</strong></div>
                            <div class="col-sm">{{ $donor->country_name }}</div>
                        </div>
                    </li>
                @endisset

                @isset($donor->email)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('app.email')</strong></div>
                            <div class="col-sm">
                                <a href="mailto:{{ $donor->email }}">{{ $donor->email }}</a>
                            </div>
                        </div>
                    </li>
                @endisset

                @isset($donor->phone)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('fundraising.phone')</strong></div>
                            <div class="col-sm">
                                <a href="tel:{{ $donor->phone }}">{{ $donor->phone }}</a>
                            </div>
                        </div>
                    </li>
                @endisset

                @isset($donor->language)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('fundraising.correspondence_language')</strong></div>
                            <div class="col-sm">
                                {{ $donor->language }}
                            </div>
                        </div>
                    </li>
                @endisset

                @isset($donor->remarks)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('app.remarks')</strong></div>
                            <div class="col-sm">
                                <em>{!! nl2br(e($donor->remarks)) !!}</em>
                            </div>
                        </div>
                    </li>
                @endisset

                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm"><strong>@lang('app.registered')</strong></div>
                        <div class="col-sm">{{ $donor->created_at }} <small class="text-muted pl-2">{{ $donor->created_at->diffForHumans() }}</small></div>
                    </div>
                </li>

                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm"><strong>@lang('app.last_updated')</strong></div>
                        <div class="col-sm">{{ $donor->updated_at }} <small class="text-muted pl-2">{{ $donor->created_at->diffForHumans() }}</small></div>
                    </div>
                </li>

            </ul>

        </div>

        <div class="col-md mb-4">
            @can('create', App\Donation::class)
                @include('fundraising.donations.register_card')
            @endcan
            @can('list', App\Donation::class)
                @include('fundraising.donations.list_card')
            @endcan
        </div>
    </div>

@endsection

@section('script')
    $(function(){
        $('#currency').on('change', function(){
            $('#amount').focus();
        });
    });
@endsection
