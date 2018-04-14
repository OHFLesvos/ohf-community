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
                            <div class="col-sm"><strong>@lang('app.company')</strong></div>
                            <div class="col-sm">{{ $donor->company }}</div>
                        </div>
                    </li>
                @endisset

                @isset($donor->street)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('app.street')</strong></div>
                            <div class="col-sm">{{ $donor->street }}</div>
                        </div>
                    </li>
                @endisset

                @isset($donor->zip)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('app.zip')</strong></div>
                            <div class="col-sm">{{ $donor->zip }}</div>
                        </div>
                    </li>
                @endisset

                @isset($donor->city)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('app.city')</strong></div>
                            <div class="col-sm">{{ $donor->city }}</div>
                        </div>
                    </li>
                @endisset

                @isset($donor->country_name)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('app.country')</strong></div>
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
                            <div class="col-sm"><strong>@lang('app.phone')</strong></div>
                            <div class="col-sm">
                                <a href="tel:{{ $donor->phone }}">{{ $donor->phone }}</a>
                            </div>
                        </div>
                    </li>
                @endisset

                @isset($donor->language)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm"><strong>@lang('app.correspondence_language')</strong></div>
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

            {{-- Register new donation --}}
            @can('create', App\Donation::class)
                <div class="card mb-4">
                    <div class="card-header">
                        @lang('fundraising.register_new_donation')
                    </div>
                    <div class="card-body pb-0">
                        {!! Form::open(['route' => ['fundraising.donations.store', $donor ]]) !!}
                            <div class="form-row">
                                <div class="col-md">
                                    {{ Form::bsDate('date', Carbon\Carbon::now()->toDateString(), [ 'required', 'max' => Carbon\Carbon::today()->toDateString() ], '') }}
                                </div>
                                <div class="col-md-auto">
                                    {{ Form::bsSelect('currency', $currencies, Config::get('fundraising.base_currency'), [ 'required', 'id' => 'currency' ], '') }}
                                </div>
                                <div class="col-md">
                                    {{ Form::bsNumber('amount', null, [ 'required', 'placeholder' => __('app.amount'), 'step' => 'any', 'id' => 'amount' ], '') }}
                                </div>
                                <div class="col-md" id="exchange_rate_container">
                                    {{ Form::bsNumber('exchange_rate', null, [ 'placeholder' => __('fundraising.optional_exchange_rate'), 'step' => 'any', 'title' => __('fundraising.leave_empty_for_automatic_calculation') ], '') }}
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md">
                                    {{ Form::bsText('channel', null, [ 'required', 'placeholder' => __('fundraising.channel'), 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($channels)) ], '') }}
                                </div>
                                <div class="col-md">
                                    {{ Form::bsText('purpose', null, [ 'placeholder' => __('fundraising.purpose') ], '') }}
                                </div>
                                <div class="col-md">
                                    {{ Form::bsText('reference', null, [ 'placeholder' => __('fundraising.reference') ], '') }}
                                </div>
                            </div>
                            <p>
                                {{ Form::bsSubmitButton(__('app.add')) }}
                            </p>
                        {!! Form::close() !!}
                    </div>
                </div>
            @endcan

            {{--  Individual donations  --}}
            @can('list', App\Donation::class)
                @if( ! $donations->isEmpty() )
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mt-2">
                            <thead>
                                <tr>
                                    <th>@lang('app.date')</th>
                                    <th class="d-none d-sm-table-cell">@lang('fundraising.channel')</th>
                                    <th>@lang('fundraising.purpose')</th>
                                    <th class="d-none d-sm-table-cell">@lang('fundraising.reference')</th>
                                    <th class="text-right">@lang('app.amount')</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($donations as $donation)
                                    <tr>
                                        <td><a href="{{ route('fundraising.donations.edit', [$donor, $donation]) }}">{{ $donation->date }}</a></td>
                                        <td class="d-none d-sm-table-cell">{{ $donation->channel }}</td>
                                        <td>{{ $donation->purpose }}</td>
                                        <td class="d-none d-sm-table-cell">{{ $donation->reference }}</td>
                                        <td class="text-right">
                                            {{ $donation->currency }} {{ $donation->amount }}
                                            @if($donation->currency != Config::get('fundraising.base_currency'))
                                                ({{ Config::get('fundraising.base_currency') }} {{ $donation->exchange_amount }})
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $donations->links() }}
                    
                    {{--  Donations per year  --}}
                    <table class="table table-sm mt-5">
                        <thead>
                            <tr>
                                <th>@lang('app.year')</th>
                                <th class="text-right">@lang('app.amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($donor->donationsPerYear() as $donation)
                                <tr>
                                    <td>{{ $donation->year }}</td>
                                    <td class="text-right" style="text-decoration: underline;">
                                        {{ Config::get('fundraising.base_currency') }}
                                        {{ $donation->total }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                
                @else
                    <div class="alert alert-info m-0">
                        @lang('fundraising.no_donations_found')
                    </div>
                @endif
            
            @endcan
        </div>
    </div>

@endsection

@section('script')
    function toggleExchangeAmount() {
        if ($('#currency').val() != '{{ Config::get('fundraising.base_currency') }}') {
            $('#exchange_rate_container').show();
        } else {
            $('#exchange_rate_container').hide();
        }
    }

    $(function(){
        $('#currency').on('change', function(){
            $('#amount').focus();
            toggleExchangeAmount();
        });
        toggleExchangeAmount();
    });
@endsection
