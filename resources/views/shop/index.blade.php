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
                    <div class="card-header">
                        Card {{ substr($code, 0, 7) }}
                        <span class="pull-right">Registered {{ $handout->date }}</span>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-auto">
                                <span class="display-4">
                                @if($handout->person->gender == 'f')@icon(female)
                                @elseif($handout->person->gender == 'm')@icon(male)
                                @endif
                                </span>
                            </div>
                            <div class="col-auto mb-4 mb-sm-0">
                                <a href="{{ route('people.show', $handout->person) }}" target="_blank">{{ $handout->person->family_name }} {{ $handout->person->name }}</a><br>
                                @if(isset($handout->person->date_of_birth)){{ $handout->person->date_of_birth }} (age {{ $handout->person->age }})@endif<br>
                                @if($handout->person->nationality != null){{ $handout->person->nationality }}@endif
                            </div>
                            <div class="col-sm">
                                @if($redeemed != null)
                                    @component('components.alert.warning')
                                        Card already redeemed {{ $handout->updated_at->diffForHumans() }}
                                    @endcomponent                                
                                @else
                                    @component('components.alert.success')
                                        Card valid.
                                    @endcomponent                                
                                @endif
                            </div>
                        </div>

                        
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
