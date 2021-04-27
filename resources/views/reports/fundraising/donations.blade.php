@extends('layouts.app')

@section('title', __('Report') . ': ' . __('Fundraising'))

@section('content')
    <div id="reports-app">
        <reports-app>
            @lang('Loading...')
        </reports-app>
    </div>
@endsection
