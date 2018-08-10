@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')

    <div id="shop-container">

        @if ($handout != null)
            <p>Registered: {{ $handout->date }}</p>
            @if ($redeemed != null)
                @component('components.alert.warning')
                    Redeemed: {{ $redeemed }}
                @endcomponent
            @else
                @component('components.alert.success')
                    Valid!
                @endcomponent
            @endif
            <p>Person: @include('people.person-label', ['person' => $handout->person ])</p>
        @else
            @component('components.alert.warning')
			    Card not registered.
		    @endcomponent
        @endif
        <p class="text-center">
            <button type="button" class="btn btn-lg btn-primary check-shop-card">Scan other card</button>
        </p>
    </div>


@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
