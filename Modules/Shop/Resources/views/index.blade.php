@extends('layouts.app')

@section('title', __('shop::shop.shop'))

@section('content')

    <div id="shop-app">
        @php
            $lang_arr = lang_arr([
                'shop::shop.card_not_registered',
                'shop::shop.person_assigned_to_card_has_been_deleted',
                'shop::shop.cancel_card',
                'shop::shop.redeem',
                'shop::shop.scan_card',
                'shop::shop.card',
                'shop::shop.registered',
                'shop::shop.should_card_be_cancelled',
                'shop::shop.card_already_redeemed',
                'shop::shop.card_expired'
            ]);
        @endphp
        <shop-app
            get-card-url="{{ route('shop.getCard') }}"
            :lang='@json($lang_arr)'
        ></shop-app>
    </div>

    @if(count($redeemed_cards) > 0)
        <h4 class="mb-3">@lang('shop::shop.redeemed_cards') (@lang('shop::shop.num_today', [ 'num' => count($redeemed_cards) ]))</h4>
        <table class="table table-sm table-striped mb-4">
            @foreach($redeemed_cards as $rc)
                <tr @if($rc->code == $code) class="table-info" @endif>
                    <td>
                        <a href="{{ route('shop.index') }}?code={{ $rc->code }}">@include('people::person-label', ['person' => $rc->person ])</a>
                    </td>
                    <td>{{ $rc->updated_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        </table>
    @endif

@endsection

@section('script')
    var scannerDialogTitle = '@lang('people::people.qr_code_scanner')';
    var scannerDialogWaitMessage = '@lang('app.please_wait')';
@endsection

@section('footer')
    <script src="{{ asset('js/shop.js') }}?v={{ $app_version }}"></script>
@endsection