@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')

    <div id="shop-container">
        <p class="text-center">
            <button type="button" class="btn btn-lg btn-primary check-shop-card">@lang('people.scan')</button>
        </p>
    </div>

@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
