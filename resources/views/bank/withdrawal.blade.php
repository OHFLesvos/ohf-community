@extends('bank.layout')

@section('title', __('people.bank'))

@section('wrapped-content')

    @include('bank.person-search')

    <div id="stats" class="text-center lead my-5">
        @if($numberOfPersonsServed > 0)
            @lang('people.num_persons_served_handing_out_coupons', [
                'persons' => $numberOfPersonsServed,
                'coupons' => $numberOfCouponsHandedOut,
            ])
        @else
            @lang('people.not_yet_served_any_persons')
        @endif
    </div>

@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection