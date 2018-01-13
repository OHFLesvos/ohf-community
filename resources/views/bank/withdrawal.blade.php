@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    @include('bank.person-search')

    <div id="stats" class="text-center lead my-5">
        @if($numberOfPersonsServed > 0)
            Today, we served <strong>{{ $numberOfPersonsServed }}</strong> persons, 
            handing out <strong>{{ $transactionValue }}</strong> drachmas.        
        @else
            We did not yet serve any persons today.
        @endif
    </div>

@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
@endsection

@section('footer')
    <script src="{{asset('js/bank.js')}}?v={{ $app_version }}"></script>
@endsection