@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')

    <p>{{ $handout->date }}</p>
    <p>{{ $handout->date }}</p>
    @include('people.person-label', ['person' => $handout->person ])

@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
