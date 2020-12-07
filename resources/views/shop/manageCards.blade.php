@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')
    <div id="shop-app">
        <shop-card-manager-page>
            @lang('app.loading')
        </shop-card-manager-page>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/shop.js') }}"></script>
@endpush
