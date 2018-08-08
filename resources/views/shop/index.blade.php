@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')

    <div class="text-center">
        <button type="button" class="btn btn-lg btn-primary">@lang('people.scan')</button>
    </div>

@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
    var qrCodeScannerLabel = '@lang('people.qr_code_scanner')';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
