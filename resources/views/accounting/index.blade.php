@extends('layouts.app')

@section('title', __('Accounting'))

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
        'manage-suppliers' => Gate::allows('manage-suppliers'),
    ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
@endpush
