@extends('layouts.app')

@section('title', __('donations.donors'))

@section('content')

    {!! Form::open(['route' => ['donors.index'], 'method' => 'get']) !!}
        <div class="input-group">
            {{ Form::search('filter', isset($filter) ? $filter : null, [ 'class' => 'form-control focus-tail', 'autofocus', 'placeholder' => __('donations.search_for_name') . '...' ]) }}
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">@icon(search)</button> 
                @if(isset($filter))
                    <a class="btn btn-secondary" href="{{ route('donors.index') }}">@icon(eraser)</a> 
                @endif
            </div>
        </div>
        <br>
    {!! Form::close() !!}

    @if( ! $donors->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.name')</th>
                        <th class="d-none d-md-table-cell">@lang('donations.address')</th>
                        <th class="d-none d-md-table-cell">@lang('donations.zip')</th>
                        <th class="d-none d-md-table-cell">@lang('donations.city')</th>
                        <th class="d-none d-md-table-cell">@lang('donations.country')</th>
                        <th class="d-none d-sm-table-cell">@lang('app.email')</th>
                        <th class="d-none d-sm-table-cell">@lang('donations.phone')</th>
                        @can('list', App\Donation::class)
                            @php
                                $currenciesLast = App\Donation::currenciesPerYear(Carbon\Carbon::now()->subYear()->year);
                                $countLast = $currenciesLast->count();
                                $currenciesCurrent = App\Donation::currenciesPerYear(Carbon\Carbon::now()->year);
                                $countCurrent = $currenciesCurrent->count();
                            @endphp
                            @if($countLast > 0)
                                <th colspan="{{ $countLast }}">@lang('donations.donations') {{ Carbon\Carbon::now()->subYear()->year }}</th>
                            @endif
                            @if($countCurrent > 0)
                                <th colspan="{{ $countCurrent }}">@lang('donations.donations') {{ Carbon\Carbon::now()->year }}</th>
                            @endif
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donors as $donor)
                        <tr>
                            <td>
                                <a href="{{ route('donors.show', $donor) }}">{{ $donor->name }}</a>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $donor->address }}</td>
                            <td class="d-none d-md-table-cell">{{ $donor->zip }}</td>
                            <td class="d-none d-md-table-cell">{{ $donor->city }}</td>
                            <td class="d-none d-md-table-cell">{{ $donor->country }}</td>
                            <td class="d-none d-sm-table-cell">
                                @isset($donor->email)
                                    <a href="mailto:{{ $donor->email }}">{{ $donor->email }}</a>
                                @endisset
                            </td>
                            <td class="d-none d-sm-table-cell">
                                @isset($donor->phone)
                                    <a href="mailto:{{ $donor->phone }}">{{ $donor->phone }}</a>
                                @endisset
                            </td>
                            @can('list', App\Donation::class)
                                @foreach($currenciesLast as $currency)
                                    @php
                                        $donationsLast = $donor->amountPerYear(Carbon\Carbon::now()->subYear()->year, $currency);
                                    @endphp
                                    <td class="text-right">
                                        @isset($donationsLast)
                                            {{ $donationsLast->currency }} {{ $donationsLast->total }}
                                        @endisset
                                    </td>
                                @endforeach
                                @foreach($currenciesCurrent as $currency)
                                    @php
                                        $donationsCurrent = $donor->amountPerYear(Carbon\Carbon::now()->year, $currency);
                                    @endphp
                                    <td class="text-right">
                                        @isset($donationsCurrent)
                                            {{ $donationsCurrent->currency }} {{ $donationsCurrent->total }}
                                        @endisset
                                    </td>
                                @endforeach
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="float-right"><small>@lang('app.total'): {{ $donors->count() }}</small></div>
        {{ $donors->links() }}
    @else
        @component('components.alert.info')
            @lang('donations.no_donors_found')
        @endcomponent
	@endif
	
@endsection
