@extends('bank.layout')

@section('title', __('people.bank'))

@section('wrapped-content')

    @include('bank.person-search')

    @if(count($results) > 0)
        @foreach ($results as $person)
            @include('bank.person-card')
        @endforeach
        {{ $results->appends(['filter' => $filter])->links() }}
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
    var handoutCouponUrl = '{{ route('bank.handoutCoupon') }}';
    var undoHandoutCouponUrl = '{{ route('bank.undoHandoutCoupon') }}';
    var updateGenderUrl = '{{ route('bank.updateGender') }}';
    var updateDateOfBirthUrl = '{{ route('bank.updateDateOfBirth') }}';
    var registerCardUrl = '{{ route('bank.registerCard') }}';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
