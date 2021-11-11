@extends('layouts.app')

@section('content')
    <div id="app">
        <x-spinner />
    </div>
@endsection

@push('head')
    @php
        $permissions = [
            'view-transactions' => optional(Auth::user())->can('viewAny', App\Models\Accounting\Transaction::class) ?? false,
            'configure-accounting' => Gate::allows('configure-accounting'),
            'view-accounting-categories' => optional(Auth::user())->can('viewAny', App\Models\Accounting\Category::class) ?? false,
            'view-accounting-projects' => optional(Auth::user())->can('viewAny', App\Models\Accounting\Project::class) ?? false,
            'create-transactions' => optional(Auth::user())->can('create', App\Models\Accounting\Transaction::class) ?? false,
            'view-accounting-summary' => Gate::allows('view-accounting-summary'),
            'export-to-webling' => optional(Auth::user())->can('book-accounting-transactions-externally') ?? false
                        && config('services.webling.api_url') !== null
                        && config('services.webling.api_key') !== null,
            'view-suppliers' => optional(Auth::user())->can('viewAny',  App\Models\Accounting\Supplier::class) ?? false,
            'manage-suppliers' => Gate::allows('manage-suppliers'),
            'view-fundraising-entities' => Gate::allows('view-fundraising-entities'),
            'manage-fundraising-entities' => Gate::allows('manage-fundraising-entities'),
            'view-fundraising-reports' => Gate::allows('view-fundraising-reports'),
            'view-community-volunteer-reports' => Gate::allows('view-community-volunteer-reports'),
            'view-fundraising-reports' => Gate::allows('view-fundraising-reports'),
            'register-visitors' => Gate::allows('register-visitors'),
            'view-budgets' => optional(Auth::user())->can('viewAny',  App\Models\Accounting\Budget::class) ?? false,
            'manage-budgets' => Gate::allows('manage-budgets'),
        ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions);
        window.Laravel.title = "{{ config('app.name') }} - {{ config('app.product_name') }}";
    </script>
@endpush
