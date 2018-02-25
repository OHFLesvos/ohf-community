@extends('layouts.app')

@section('title', __('donations.show_donor'))

@section('content')

    <div class="row">

        <div class="col-md mb-4">
            <div class="card">
                <div class="card-header">@lang('donations.donor')</div>
                <div class="card-body p-0">

                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th>@lang('app.name')</th>
                                <td>{{ $donor->name }}</td>
                            </tr>
                            <tr>
                                <th>@lang('donations.address')</th>
                                <td>{{ $donor->address }}</td>
                            </tr>
                            <tr>
                                <th>@lang('donations.zip')</th>
                                <td>{{ $donor->zip }}</td>
                            </tr>
                            <tr>
                                <th>@lang('donations.city')</th>
                                <td>{{ $donor->city }}</td>
                            </tr>
                            <tr>
                                <th>@lang('donations.country')</th>
                                <td>{{ $donor->country }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.email')</th>
                                <td>
                                    @isset($donor->email)
                                        <a href="mailto:{{ $donor->email }}">{{ $donor->email }}</a>
                                    @endisset
                                </td>
                            </tr>
                            <tr>
                                <th>@lang('donations.phone')</th>
                                <td>
                                    @isset($donor->phone)
                                        <a href="tel:{{ $donor->phone }}">{{ $donor->phone }}</a>
                                    @endisset
                                </td>
                            </tr>
                            <tr>
                                <th>@lang('app.registered')</th>
                                <td>{{ $donor->created_at }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.last_updated')</th>
                                <td>{{ $donor->updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="col-md mb-4">

            @can('create', App\Donation::class)
                <div class="card mb-4">
                    <div class="card-header">@lang('donations.register_new_donation')</div>
                    <div class="card-body pb-0">

                        {!! Form::open(['route' => ['donors.storeDonation', $donor ]]) !!}
                            <div class="form-row">
                                <div class="col-md">
                                    {{ Form::bsDate('date', Carbon\Carbon::now(), [ 'required' ], '') }}
                                </div>
                                <div class="col-md">
                                    {{ Form::bsText('origin', null, [ 'required', 'placeholder' => __('donations.origin'), 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($origins)) ], '') }}
                                </div>
                                <div class="col-md">
                                    {{ Form::bsSelect('currency', $currencies, null, [ 'required' ], '') }}
                                </div>
                                <div class="col-md">
                                    {{ Form::bsNumber('amount', null, [ 'required', 'placeholder' => __('donations.amount'), 'step' => 'any' ], '') }}
                                </div>
                            </div>
                            <p>
                                {{ Form::bsSubmitButton(__('app.add')) }}
                            </p>
                        {!! Form::close() !!}
                    </div>
                </div>
            @endcan

            @can('list', App\Donation::class)
                <div class="card">
                    <div class="card-header">@lang('donations.donations')</div>
                    <div class="card-body">
                        @if( ! $donations->isEmpty() )
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Origin</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($donations as $donation)
                                        <tr>
                                            <td>{{ $donation->date }}</td>
                                            <td>{{ $donation->origin }}</td>
                                            <td class="text-right">{{ $donation->currency }} {{ $donation->amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $donations->links() }}
                        @else
                            <div class="alert alert-info m-0">
                                @lang('donations.no_donations_found')
                            </div>
                        @endif

                    </div>
                </div>
            @endcan
        </div>
    </div>

@endsection
