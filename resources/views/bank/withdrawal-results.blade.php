@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    @include('bank.person-search')

    @if(count($results) > 0)
        @foreach ($results as $person)
            @include('bank.person-card')
        @endforeach
        {{ $results->appends(['filter' => $filter])->links('vendor.pagination.bootstrap-4') }}
    @else
        @if(isset($message))
            @component('components.alert.error')
                {{ $message }}
            @endcomponent
        @else
            @component('components.alert.info')
                Not found.
                <a href="{{ route('people.create') }}?{{ $register }}">Register a new person</a>
            @endcomponent
        @endif
    @endif

@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
    var storeTransactionUrl = '{{ route('bank.storeTransaction') }}';
    var giveBouqiqueCouponUrl = '{{ route('bank.giveBoutiqueCoupon') }}';
    var giveDiapersCouponUrl = '{{ route('bank.giveDiapersCoupon') }}';
    var updateGenderUrl = '{{ route('bank.updateGender') }}';
    var registerCardUrl = '{{ route('bank.registerCard') }}';
@endsection

@section('footer')
    <script src="{{asset('js/bank.js')}}?v={{ $app_version }}"></script>
@endsection
