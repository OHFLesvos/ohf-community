@extends('bank.layout')

@section('title', __('people.bank'))

@section('wrapped-content')

    <div id="bank-container">

        @include('bank.person-search')

        @if(count($results) > 0)
            @php
                $ids = [];
            @endphp
            @foreach ($results as $person)
                @php
                    if (in_array($person->id, $ids)) {
                        continue;
                    }
                    $members = $person->otherFamilyMembers;
                @endphp
                @include('bank.person-card', [ 'bottom_margin' => $members->count() > 0 ? 1 : 4 ])
                @if ($members->count() > 0)
                    @foreach($members as $member)
                        @php
                            $ids[] = $member->id;
                        @endphp
                        @include('bank.person-card', [ 'person' => $member, 'bottom_margin' => $loop->last ? 4 : 1, 'border' => 'info' ])
                    @endforeach
                @endif
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

    </div>

@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
    var handoutCouponUrl = '{{ route('bank.handoutCoupon') }}';
    var undoHandoutCouponUrl = '{{ route('bank.undoHandoutCoupon') }}';
    var updateGenderUrl = '{{ route('bank.updateGender') }}';
    var updateDateOfBirthUrl = '{{ route('bank.updateDateOfBirth') }}';
    var registerCardUrl = '{{ route('bank.registerCard') }}';
    var undoLabel = '@lang('app.undo')';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
