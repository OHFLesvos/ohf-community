@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Edit transaction #:number', ['number' => $transaction->receipt_no]))

@section('content')
    <div id="app">
        <x-spinner />
    </div>
@endsection
