@extends('layouts.donots-donations')

@section('title', __('donations.donations'))

@section('wrapped-content')

    @if( ! $donations->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="fit">@lang('donations.date')</th>
                        <th class="text-right fit">@lang('app.amount')</th>
                        <th>@lang('donations.donor')</th>
                        <th class="d-none d-sm-table-cell">@lang('donations.channel')</th>
                        <th>@lang('donations.purpose')</th>
                        <th class="d-none d-sm-table-cell">@lang('donations.reference')</th>
                        <th class="fit">@lang('app.registered')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donations as $donation)
                        <tr>
                            <td class="fit"><a href="{{ route('donations.edit', [$donation->donor, $donation]) }}">{{ $donation->date }}</a></td>
                            <td class="text-right fit">
                                {{ $donation->currency }} {{ $donation->amount }}
                                @if($donation->currency != Config::get('donations.base_currency'))
                                    ({{ Config::get('donations.base_currency') }} {{ $donation->exchange_amount }})
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('donors.show', $donation->donor) }}">{{ $donation->donor->name }}</a>
                            </td>
                            <td class="d-none d-sm-table-cell">{{ $donation->channel }}</td>
                            <td>{{ $donation->purpose }}</td>
                            <td class="d-none d-sm-table-cell">{{ $donation->reference }}</td>
                            <td class="d-none d-sm-table-cell fit">{{ $donation->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $donations->links() }}
    @else
        @component('components.alert.info')
            @lang('donations.no_donations_found')
        @endcomponent
	@endif
	
@endsection
