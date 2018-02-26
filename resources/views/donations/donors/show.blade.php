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
