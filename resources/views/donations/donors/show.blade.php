@extends('layouts.app')

@section('title', __('donations.show_donor'))

@section('content')

    <table class="table">
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
                <th>@lang('app.registered')</th>
                <td>{{ $donor->created_at }}</td>
            </tr>
            <tr>
                <th>@lang('app.last_updated')</th>
                <td>{{ $donor->updated_at }}</td>
            </tr>
        </tbody>
    </table>

@endsection
