@extends('layouts.app')

@section('title', __('accounting.show_transaction'))

@section('content')

    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('app.date')</strong></div>
                <div class="col-sm">
                    {{ $transaction->date }}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('app.amount')
                    @if($transaction->type == 'income') (@lang('accounting.income')) @endif
                    @if($transaction->type == 'spending') (@lang('accounting.spending')) @endif
                </strong></div>
                <div class="col-sm @if($transaction->type == 'income') text-success @elseif($transaction->type == 'spending') text-danger @endif">
                    {{ $transaction->amount }}
                </div>
            </div>
        </li>
        @isset($transaction->receipt_no)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting.receipt') #</strong></div>
                    <div class="col-sm">
                        {{ $transaction->receipt_no }}
                    </div>
                </div>
            </li>
        @endisset
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('accounting.beneficiary')</strong></div>
                <div class="col-sm">
                    {{ $transaction->beneficiary }}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('app.project')</strong></div>
                <div class="col-sm">
                    {{ $transaction->project }}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('app.description')</strong></div>
                <div class="col-sm">
                    {{ $transaction->description }}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('app.registered')</strong></div>
                <div class="col-sm">
                    @php
                        $audit = $transaction->audits()->latest()->first();
                    @endphp
                    {{ $transaction->created_at }} @isset($audit)({{ $audit->getMetadata()['user_name'] }})@endisset
                </div>
            </div>
        </li>
        @isset($transaction->receipt_picture)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting.receipt')</strong></div>
                    <div class="col-sm">
                        <img src="{{ Storage::url($transaction->receipt_picture) }}" style="max-width:100%">
                    </div>
                </div>
            </li>
        @endisset
    </ul>

@endsection
