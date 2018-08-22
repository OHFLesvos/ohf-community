@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')

    <div id="shop-container">

        <p>
            <button type="button" class="btn btn-lg btn-block btn-primary check-shop-card">
                @icon(qrcode)
                @if($code != null)
                    @lang('shop.scan_another_card')
                @else
                    @lang('shop.scan_card')
                @endif
            </button>
        </p>

        @if($code != null)
            @if($handout != null)
                <div class="card mb-4">
                    <div class="card-header">
                        @lang('shop.card') {{ substr($code, 0, 7) }}
                        <span class="pull-right">
                            <span class="d-none d-sm-inline">@lang('shop.registered')</span> {{ $handout->date }}
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
                                        {{ $handout->person->family_name }} {{ $handout->person->name }} 
                                        <a href="{{ route('people.show', $handout->person) }}" target="_blank">@icon(search)</a><br>
                                        @if(isset($handout->person->date_of_birth)){{ $handout->person->date_of_birth }} (age {{ $handout->person->age }})@endif<br>
                                        @if($handout->person->nationality != null){{ $handout->person->nationality }}@endif
                                        @php
                                            $children = $handout->person->children;
                                        @endphp
                                        @if(count($children) > 0)
                                            @foreach($children as $child)
                                                <br>@icon(child) {{ $child->family_name }} {{ $child->name }} (age {{ $child->age }})
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto text-center">
                                @if($handout->code_redeemed != null)
                                    <strong class="text-warning">
                                        @icon(warning) @lang('shop.card_already_redeemed')<br>
                                        <small>{{ $handout->updated_at->diffForHumans() }}</small>
                                    </strong>
                                @elseif($expired)
                                    <strong class="text-warning">
                                        @icon(warning) @lang('shop.card_expired')<br>
                                    </strong>
                                @else
                                    {{ Form::open(['route' => 'shop.redeem']) }}
                                    {{ Form::hidden('code', $code) }}
                                    <button type="submit" class="btn btn-lg btn-block btn-success">
                                        @icon(check) @lang('shop.redeem')
                                    </button>
                                    {{ Form::close() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @component('components.alert.warning')
                    @lang('shop.card_not_registered')
                @endcomponent
            @endif
        @endif

        @if(count($redeemed_cards) > 0)
            <h4 class="mb-3">@lang('shop.redeemed_cards') (@lang('shop.num_today', [ 'num' => count($redeemed_cards) ]))</h4>
            <table class="table table-sm table-striped mb-4">
                @foreach($redeemed_cards as $rc)
                    <tr>
                        <td>
                            <a href="{{ route('shop.index') }}?code={{ $rc->code }}">@include('people.person-label', ['person' => $rc->person ])</a>
                        </td>
                        <td>{{ $rc->updated_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </table>
        @endif

    </div>

@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
    var shopUrl = '{{ route('shop.index') }}';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
