@extends('layouts.app')

@section('title', __('donations.donors'))

@section('content')

    @if( ! $donors->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.name')</th>
                        <th>@lang('donations.address')</th>
                        <th>@lang('donations.zip')</th>
                        <th>@lang('donations.city')</th>
                        <th>@lang('donations.country')</th>
                        <th>@lang('app.email')</th>
                        <th>@lang('app.registered')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donors as $donor)
                        <tr>
                            <td>
                                <a href="{{ route('donors.show', $donor) }}">{{ $donor->name }}</a>
                            </td>
                            <td>{{ $donor->address }}</td>
                            <td>{{ $donor->zip }}</td>
                            <td>{{ $donor->city }}</td>
                            <td>{{ $donor->country }}</td>
                            <td>
                                @isset($donor->email)
                                    <a href="mailto:{{ $donor->email }}">{{ $donor->email }}</a>
                                @endisset
                            </td>
                            <td>{{ $donor->created_at }}</td>
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
