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
        'create-transactions' => Auth::user()->can('create', App\Models\Accounting\Transaction::class),
    ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
@endpush
