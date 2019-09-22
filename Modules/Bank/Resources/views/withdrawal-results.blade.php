@extends('bank::layout')

@section('title', __('bank::bank.bank'))

@section('wrapped-content')

    <div id="bank-container">

        @include('bank::person-search')

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
                @include('bank::person-card', [ 'bottom_margin' => $members->count() > 0 ? 0 : 5 ])
                @if ($members->count() > 0)
                    @foreach($members->sortByDesc('age') as $member)
                        @php
                            $ids[] = $member->id;
                        @endphp
                        @include('bank::person-card', [ 'person' => $member, 'bottom_margin' => $loop->last ? 5 : 0 ])
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
                    @lang('app.not_found').
                @endcomponent
                @can('create', Modules\People\Entities\Person::class)
                <p>
                    <a href="{{ route('people.create') }}?{{ $register }}" class="btn btn-primary">
                        @icon(plus-circle)
                        @lang('people::people.register_a_new_person')
                    </a>
                </p>
                @endcan
            @endif
        @endif

    </div>

@endsection

@section('script')
    var undoLabel = '@lang('app.undo')';
    var scannerDialogTitle = '@lang('people::people.qr_code_scanner')';
    var scannerDialogWaitMessage = '@lang('app.please_wait')';
    @if(count($results) > 0 && count($terms) > 0)
        var highlightTerms = @json($terms)
    @endif
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
