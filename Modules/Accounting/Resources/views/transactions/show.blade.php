@extends('layouts.app')

@section('title', __('accounting::accounting.show_transaction'))

@section('content')

    @if($transaction->receipt_pictures)
        <div class="row">
            <div class="col-md-8">
    @endif
    
    @include('accounting::transactions.snippet')

    @if(!empty($transaction->receipt_pictures))
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ Storage::url($transaction->receipt_pictures[0]) }}" data-lity><img src="{{ Storage::url($transaction->receipt_pictures[0]) }}" class="img-fluid img-thumbnail"></a>
        </div>
    </div>
    @endif

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
