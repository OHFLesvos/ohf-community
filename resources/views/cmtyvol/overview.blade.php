@extends('layouts.app')

@section('title', __('app.community_volunteers'))

@section('content')
    <div id="cmtyvol-app">
        <community-volunteers-overview-page>
            @lang('app.loading')
        </community-volunteers-overview-page>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/cmtyvol.js') }}"></script>
@endpush
