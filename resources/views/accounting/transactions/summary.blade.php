@extends('layouts.app')

@section('title', __('Summary'))

@section('content')
    <div id="app">
        <x-spinner />
    </div>
@endsection

@push('head')
    @php
    $permissions = [
        'view-accounting-summary' => Gate::allows('view-accounting-summary'),
        'can-view-transactions' => Auth::user()->can('viewAny', App\Models\Accounting\Transaction::class),
    ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions)

    </script>
@endpush
