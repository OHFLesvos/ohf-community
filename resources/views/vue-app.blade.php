@extends('layouts.app', ['no_container' => true])

@section('content')
    <div id="app">
        <div class="container-fluid py-2">
            {{-- <x-spinner /> --}}
            {{ __('Loading...') }}
        </div>
    </div>
@endsection

@push('head')
    @php
        $permissions = [
            'view-transactions' => Auth::user()->can('viewAny', App\Models\Accounting\Transaction::class),
            'configure-accounting' => Gate::allows('configure-accounting'),
            'view-accounting-categories' => Auth::user()->can('viewAny', App\Models\Accounting\Category::class),
            'view-accounting-projects' => Auth::user()->can('viewAny', App\Models\Accounting\Project::class),
            'create-transactions' => Auth::user()->can('create', App\Models\Accounting\Transaction::class),
            'view-accounting' => Auth::user()->can('viewAny', Transaction::class) || Gate::allows('view-accounting-summary'),
            'view-accounting-summary' => Gate::allows('view-accounting-summary'),
            'export-to-webling' => Auth::user()->can('book-accounting-transactions-externally')
                        && config('services.webling.api_url') !== null
                        && config('services.webling.api_key') !== null,
            'view-suppliers' => Auth::user()->can('viewAny',  App\Models\Accounting\Supplier::class),
            'manage-suppliers' => Gate::allows('manage-suppliers'),
            'view-fundraising-entities' => Gate::allows('view-fundraising-entities'),
            'manage-fundraising-entities' => Gate::allows('manage-fundraising-entities'),
            'view-reports' => Gate::allows('view-reports'),
            'view-fundraising-reports' => Gate::allows('view-fundraising-reports'),
            'view-community-volunteer-reports' => Gate::allows('view-community-volunteer-reports'),
            'view-fundraising-reports' => Gate::allows('view-fundraising-reports'),
            'register-visitors' => Gate::allows('register-visitors'),
            'view-visitors-reports' => Gate::allows('view-visitors-reports'),
            'export-visitors' => Gate::allows('export-visitors'),
            'view-budgets' => Auth::user()->can('viewAny',  App\Models\Accounting\Budget::class),
            'manage-budgets' => Gate::allows('manage-budgets'),
            'create-badges' => Gate::allows('create-badges'),
            'view-community-volunteers' => Auth::user()->can('viewAny', App\Models\CommunityVolunteers\CommunityVolunteer::class),
            'view-user-management' => Auth::user()->can('viewAny', App\Models\User::class) || Auth::user()->can('viewAny', App\Models\Role::class),
            'create-user' => Auth::user()->can('create',  App\Models\User::class),
            'create-role' => Auth::user()->can('create',  App\Models\Role::class),
        ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions);
        window.Laravel.title = "{{ config('app.name') }}";
    </script>
@endpush
