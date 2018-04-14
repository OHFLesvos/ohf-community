@extends('layouts.donots-donations')

@section('title', __('fundraising.donation_management'))

@section('wrapped-content')

    {!! Form::open(['route' => ['fundraising.donors.index'], 'method' => 'get']) !!}
        <div class="input-group">
            {{ Form::search('filter', isset($filter) ? $filter : null, [ 'class' => 'form-control focus-tail', 'autofocus', 'placeholder' => __('fundraising.search_for_name') . '...' ]) }}
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">@icon(search)</button> 
                @if(isset($filter))
                    <a class="btn btn-secondary" href="{{ route('fundraising.donors.index') }}">@icon(eraser)</a> 
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
                        <th>@lang('fundraising.first_name')</th>
                        <th>@lang('fundraising.last_name')</th>
                        <th>@lang('fundraising.company')</th>
                        <th class="d-none d-md-table-cell">@lang('fundraising.street')</th>
                        <th class="d-none d-md-table-cell">@lang('fundraising.zip')</th>
                        <th class="d-none d-md-table-cell">@lang('fundraising.city')</th>
                        <th class="d-none d-md-table-cell">@lang('fundraising.country')</th>
                        <th class="d-none d-sm-table-cell">@lang('app.email')</th>
                        <th class="d-none d-sm-table-cell">@lang('fundraising.phone')</th>
                        <th class="d-none d-sm-table-cell">@lang('fundraising.correspondence_language')</th>
                        {{-- @can('list', App\Donation::class)
                            <th class="text-right d-none d-sm-table-cell">@lang('fundraising.donations') {{ Carbon\Carbon::now()->subYear()->year }}</th>
                            <th class="text-right">@lang('fundraising.donations') {{ Carbon\Carbon::now()->year }}</th>
                        @endcan --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donors as $donor)
                        <tr>
                            <td>
                                @isset($donor->first_name)
                                    <a href="{{ route('fundraising.donors.show', $donor) }}">
                                        {{ $donor->first_name }}
                                    </a>
                                @endisset
                            </td>
                            <td>
                                @isset($donor->last_name)
                                    <a href="{{ route('fundraising.donors.show', $donor) }}">
                                        {{ $donor->last_name }}
                                    </a>
                                @endisset
                            </td>
                            <td>
                                @isset($donor->company)
                                    <a href="{{ route('fundraising.donors.show', $donor) }}">
                                        {{ $donor->company }}
                                    </a>
                                @endisset
                            </td>
                            <td class="d-none d-md-table-cell">{{ $donor->street }}</td>
                            <td class="d-none d-md-table-cell">{{ $donor->zip }}</td>
                            <td class="d-none d-md-table-cell">{{ $donor->city }}</td>
                            <td class="d-none d-md-table-cell">{{ $donor->country_name }}</td>
                            <td class="d-none d-sm-table-cell">
                                @isset($donor->email)
                                    <a href="mailto:{{ $donor->email }}">{{ $donor->email }}</a>
                                @endisset
                            </td>
                            <td class="d-none d-sm-table-cell">
                                @isset($donor->phone)
                                    <a href="tel:{{ $donor->phone }}">{{ $donor->phone }}</a>
                                @endisset
                            </td>
                            <td class="d-none d-md-table-cell">{{ $donor->language }}</td>
                            {{-- @can('list', App\Donation::class)
                                <td class="text-right d-none d-sm-table-cell">
                                    {{ Config::get('fundraising.base_currency') }}
                                    {{ $donor->amountPerYear(Carbon\Carbon::now()->subYear()->year) ?? 0 }}
                                </td>
                                <td class="text-right">
                                    {{ Config::get('fundraising.base_currency') }}
                                    {{ $donor->amountPerYear(Carbon\Carbon::now()->year) ?? 0 }}
                                </td>
                            @endcan --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="float-right"><small>@lang('app.total'): {{ $donors->count() }}</small></div>
        {{ $donors->links() }}
    @else
        @component('components.alert.info')
            @lang('fundraising.no_donors_found')
        @endcomponent
	@endif
	
@endsection
