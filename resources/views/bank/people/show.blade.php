@extends('layouts.app')

@section('title', __('people.view_person'))

@section('content')

    @php
        $showHandoutLimit = 15;
        $handouts = $person->couponHandouts()->orderBy('created_at', 'desc')->limit($showHandoutLimit)->get();
        $firstHandout = $person->couponHandouts()->orderBy('created_at', 'asc')->first();
        $p = [
            'name' => $person->name,
            'family_name' => $person->family_name,
            'gender' => $person->gender,
            'date_of_birth' => $person->date_of_birth,
            'age' => $person->age,
            'police_no' => $person->police_no,
            'police_no_formatted' => $person->police_no_formatted,
            'nationality' => $person->nationality,
            'languages' => $person->languages,
            'portrait_picture' => $person->portrait_picture,
            'portrait_picture_url' => $person->portrait_picture != null ? Storage::url($person->portrait_picture) : null,
            'is_active_helper' => optional($person->helper)->isActive,
            'remarks' => $person->remarks,
            'card_no' => $person->card_no,
            'card_issued' => optional($person->card_issued)->toDateString(),
            'card_issued_dfh' => optional($person->card_issued)->diffForHumans(),
            'revoked_cards' => $person->revokedCards->map(fn ($card) => [
                'date' => $card->created_at->toDateString(),
                'date_dfh' => $card->created_at->diffForHumans(),
                'card_no' => $card->card_no,
            ]),
            'created_at' => $person->created_at->toDateTimeString(),
            'created_at_dfh' => $person->created_at->diffForHumans(),
            'updated_at' => $person->updated_at->toDateTimeString(),
            'updated_at_dfh' => $person->updated_at->diffForHumans(),
            'handouts' => ! $handouts->isEmpty() ? $handouts->map(fn ($handout) => [
                'id' => $handout->id,
                'date' => $handout->date,
                'amount' => $handout->couponType->daily_amount,
                'coupon_name' => $handout->couponType->name,
                'created_at' => $handout->created_at->toDateTimeString(),
                'created_at_dfh' => $handout->created_at->diffForHumans(),
            ])
            ->toArray() : [],
            'num_handouts' => $person->couponHandouts()->count(),
            'first_handout_date' => optional(optional($firstHandout)->created_at)->toDateString(),
            'first_handout_date_diff' => optional(optional($firstHandout)->created_at)->diffForHumans(),
        ];
    @endphp

    <div id="bank-app">
        <view-person-page
            :person='@json($p)'
            :show-handout-limit="{{ $showHandoutLimit }}"
        ></view-person-page>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}" defer></script>
@endsection