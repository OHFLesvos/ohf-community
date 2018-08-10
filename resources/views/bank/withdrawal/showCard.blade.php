@extends('bank.layout')

@section('title', __('people.bank'))

@section('wrapped-content')
    <div id="bank-container">

    @php
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

    <div class="text-right">
        <button class="btn btn-secondary btn" type="button" id="scan-id-button">@icon(qrcode)<span class="d-none d-sm-inline"> @lang('people.scan_another_card')</span></button> 
        <a class="btn btn-secondary btn" href="{{ route('bank.withdrawal') }}">@icon(search)<span class="d-none d-sm-inline"> @lang('people.search_persons')</span></a>
    </div>

    </div>
@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
    var handoutCouponUrl = '{{ route('bank.handoutCoupon') }}';
    var undoHandoutCouponUrl = '{{ route('bank.undoHandoutCoupon') }}';
    var updateGenderUrl = '{{ route('bank.updateGender') }}';
    var updateDateOfBirthUrl = '{{ route('bank.updateDateOfBirth') }}';
    var updateNationalityUrl = '{{ route('bank.updateNationality') }}';
    var registerCardUrl = '{{ route('bank.registerCard') }}';
    var undoLabel = '@lang('app.undo')';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
