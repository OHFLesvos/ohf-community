@extends('layouts.app')

@section('title', __('fundraising.donation_management'))

@section('content')
    <div id="fundraising-app">
        <fundraising-app></fundraising-app>
    </div>
@endsection

@section('footer')
    @php
        $permissions = [
            'view-donors' => request()->user()->can('viewAny', \App\Models\Fundraising\Donor::class),
            'create-donors' => request()->user()->can('create', \App\Models\Fundraising\Donor::class),
            'view-donations' => request()->user()->can('viewAny', \App\Models\Fundraising\Donation::class),
            'create-donations' => request()->user()->can('create', \App\Models\Fundraising\Donation::class),
            'view-fundraising-reports' => Gate::allows('view-fundraising-reports'),
        ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
