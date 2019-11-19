@extends('layouts.app')

@section('title', __('accounting::accounting.show_transaction'))

@section('content')

    @isset($transaction->receipt_picture)
        <div class="row">
            <div class="col-md-8">
    @endisset
    
    @include('accounting::transactions.snippet')

    @isset($transaction->receipt_picture)
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ Storage::url($transaction->receipt_picture) }}" data-lity><img src="{{ Storage::url($transaction->receipt_picture) }}" class="img-fluid img-thumbnail"></a>
        </div>
    </div>
    @endisset

    <p class="mt-3">
        @isset($prev_id)
            <a href="{{ route('accounting.transactions.show', $prev_id) }}" class="btn btn-sm btn-secondary">
                &lsaquo; Previous
            </a>
        @endisset
        @isset($next_id)
            <a href="{{ route('accounting.transactions.show', $next_id) }}" class="btn btn-sm btn-secondary">
                Next &rsaquo; 
            </a>
        @endisset
    </p>

@endsection
