@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')

    <div id="shop-container">

        <p class="text-center">
            <button type="button" class="btn btn-lg btn-primary check-shop-card">
                @if($code != null)
                    Scan other card
                @else
                    @lang('people.scan')
                @endif
            </button>
        </p>

        @if($code != null)
            @if($handout != null)
                <div class="card mb-4">
                    @if($redeemed != null)
                        <div class="card-header bg-warning text-dark">
                            Already redeemed {{ $redeemed }} ({{ $handout->updated_at->diffForHumans() }})
                        </div>
                    @else
                        <div class="card-header bg-success text-light">
                            Valid!
                        </div>
                    @endif
                    <div class="card-body">
                        @include('people.person-label', ['person' => $handout->person ])<br>
                        Card registered: {{ $handout->date }}
                    </div>
                </div>
            @else
                @component('components.alert.warning')
                    Card not registered.
                @endcomponent
            @endif
        @endif

    </div>

    @if(count($redeemed_cards) > 0)
        <h4 class="mb-3">Redeemed cards ({{ count($redeemed_cards) }} today)</h4>
        <table class="table table-sm table-striped mb-4">
            @foreach($redeemed_cards as $rc)
                <tr>
                    <td>@include('people.person-label', ['person' => $rc->person ])</td>
                    <td>{{ $rc->updated_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        </table>
    @endif


@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
    var shopUrl = '{{ route('shop.index') }}';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
