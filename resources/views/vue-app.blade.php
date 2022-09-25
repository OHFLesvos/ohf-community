@extends('layouts.app')

@section('content')
    <div id="app">
        <x-spinner />
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
            'view-accounting-summary' => Gate::allows('view-accounting-summary'),
            'export-to-webling' => Auth::user()->can('book-accounting-transactions-externally')
                        && config('services.webling.api_url') !== null
                        && config('services.webling.api_key') !== null,
            'view-suppliers' => Auth::user()->can('viewAny',  App\Models\Accounting\Supplier::class),
            'manage-suppliers' => Gate::allows('manage-suppliers'),
            'view-fundraising-entities' => Gate::allows('view-fundraising-entities'),
            'manage-fundraising-entities' => Gate::allows('manage-fundraising-entities'),
            'view-fundraising-reports' => Gate::allows('view-fundraising-reports'),
            'view-community-volunteer-reports' => Gate::allows('view-community-volunteer-reports'),
            'view-fundraising-reports' => Gate::allows('view-fundraising-reports'),
            'register-visitors' => Gate::allows('register-visitors'),
            'view-budgets' => Auth::user()->can('viewAny',  App\Models\Accounting\Budget::class),
            'manage-budgets' => Gate::allows('manage-budgets'),
            'view-community-volunteers' => Auth::user()->can('viewAny', App\Models\CommunityVolunteers\CommunityVolunteer::class),
        ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions);
        window.Laravel.title = "{{ config('app.name') }}";
    </script>
@endpush
