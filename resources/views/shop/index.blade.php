@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')

    <div id="shop-container">

        <p>
            <button type="button" class="btn btn-lg btn-block btn-primary check-shop-card">
                @icon(qrcode)
                @if($code != null)
                    Scan other card
                @else
                    Scan card
                @endif
            </button>
        </p>

        @if($code != null)
            @if($handout != null)
                <div class="card mb-4">
                    <div class="card-header">
                        Card {{ substr($code, 0, 7) }}
                        <span class="pull-right">
                            <span class="d-none d-sm-inline">Registered</span> {{ $handout->date }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mb-4 mb-sm-0">
                                <div class="row align-items-center">
                                    <div class="col-auto text-center">
                                        <span class="display-4">
                                            @if($handout->person->gender == 'f')@icon(female)
                                            @elseif($handout->person->gender == 'm')@icon(male)
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col">
                                        <a href="{{ route('people.show', $handout->person) }}" target="_blank">{{ $handout->person->family_name }} {{ $handout->person->name }}</a><br>
                                        @if(isset($handout->person->date_of_birth)){{ $handout->person->date_of_birth }} (age {{ $handout->person->age }})@endif<br>
                                        @if($handout->person->nationality != null){{ $handout->person->nationality }}@endif
                                        @php
                                            $children = $handout->person->children;
                                        @endphp
                                        @if(count($children) > 0)
                                            @foreach($children as $child)
                                                @icon(child) {{ $child->family_name }} {{ $child->name }} (age {{ $child->age }})<br>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto text-center">
                                @if($handout->code_redeemed != null)
                                    <strong class="text-warning">
                                        @icon(warning) Card already redeemed.<br>
                                        <small>{{ $handout->updated_at->diffForHumans() }}</small>
                                    </strong>
                                @else
                                    {{ Form::open(['route' => 'shop.redeem']) }}
                                    {{ Form::hidden('code', $code) }}
                                    <button type="submit" class="btn btn-lg btn-block btn-success check-shop-card">
                                        @icon(check) Redeem
                                    </button>
                                    {{ Form::close() }}
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
                    <td>
                        @include('people.person-label', ['person' => $rc->person ])
                    </td>
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
