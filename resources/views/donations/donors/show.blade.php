@extends('layouts.app')

@section('title', __('donations.show_donor'))

@section('content')

    <div class="row">

        <div class="col-md mb-4">
            <div class="card">
                <div class="card-header">@lang('donations.donor')</div>
                <div class="card-body p-0">

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>@lang('app.name')</strong></div>
                                <div class="col-sm">{{ $donor->name }}</div>
                            </div>
                        </li>

                        @isset($donor->address)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm"><strong>@lang('donations.address')</strong></div>
                                    <div class="col-sm">{{ $donor->address }}</div>
                                </div>
                            </li>
                        @endisset

                        @isset($donor->zip)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm"><strong>@lang('donations.zip')</strong></div>
                                    <div class="col-sm">{{ $donor->zip }}</div>
                                </div>
                            </li>
                        @endisset

                        @isset($donor->city)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm"><strong>@lang('donations.city')</strong></div>
                                    <div class="col-sm">{{ $donor->city }}</div>
                                </div>
                            </li>
                        @endisset

                        @isset($donor->country)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm"><strong>@lang('donations.country')</strong></div>
                                    <div class="col-sm">{{ $donor->country }}</div>
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
                                    <div class="col-sm"><strong>@lang('donations.phone')</strong></div>
                                    <div class="col-sm">
                                        <a href="tel:{{ $donor->phone }}">{{ $donor->phone }}</a>
                                    </div>
                                </div>
                            </li>
                        @endisset

                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>@lang('app.registered')</strong></div>
                                <div class="col-sm">{{ $donor->created_at }}</div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm"><strong>@lang('app.last_updated')</strong></div>
                                <div class="col-sm">{{ $donor->updated_at }}</div>
                            </div>
                        </li>

                    </ul>

                </div>
            </div>
        </div>

        <div class="col-md mb-4">
            @can('create', App\Donation::class)
                @include('donations.donations.register_card')
            @endcan
            @can('list', App\Donation::class)
                @include('donations.donations.list_card')
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
