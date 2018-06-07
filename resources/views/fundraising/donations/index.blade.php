@extends('layouts.donots-donations')

@section('title', __('fundraising.donation_management'))

@section('wrapped-content')

    @if( ! $donations->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="fit">@lang('app.date')</th>
                        <th class="text-right fit">@lang('app.amount')</th>
                        <th>@lang('fundraising.donor')</th>
                        <th class="d-none d-sm-table-cell">@lang('fundraising.channel')</th>
                        <th>@lang('fundraising.purpose')</th>
                        <th class="d-none d-sm-table-cell">@lang('fundraising.reference')</th>
                        <th class="fit">@lang('app.registered')</th>
                        <th class="fit" title="@lang('fundraising.thanked')">@icon(handshake-o)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donations as $donation)
                        <tr>
                            <td class="fit"><a href="{{ route('fundraising.donations.edit', [$donation->donor, $donation]) }}">{{ $donation->date }}</a></td>
                            <td class="text-right fit">
                                {{ $donation->currency }} {{ $donation->amount }}
                                @if($donation->currency != Config::get('fundraising.base_currency'))
                                    ({{ Config::get('fundraising.base_currency') }} {{ $donation->exchange_amount }})
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('fundraising.donors.show', $donation->donor) }}">{{ $donation->donor->full_name }}</a>
                            </td>
                            <td class="d-none d-sm-table-cell">{{ $donation->channel }}</td>
                            <td>{{ $donation->purpose }}</td>
                            <td class="d-none d-sm-table-cell">{{ $donation->reference }}</td>
                            <td class="d-none d-sm-table-cell fit">{{ $donation->created_at }}</td>
                            @if($donation->thanked != null)
                                <td class="fit" title="{{ $donation->thanked->toDateString() }}">
                                    @icon(check)
                                </td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $donations->links() }}
    @else
        @component('components.alert.info')
            @lang('fundraising.no_donations_found')
        @endcomponent
	@endif
	
@endsection
