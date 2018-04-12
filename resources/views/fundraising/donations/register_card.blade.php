<div class="card mb-4">
    <div class="card-header">
        @lang('fundraising.register_new_donation')
        <a class="pull-right" href="{{ route('fundraising.donations.create', $donor)  }}">@icon(arrows-alt)</a>
    </div>
    <div class="card-body pb-0">
        {!! Form::open(['route' => ['fundraising.donations.store', $donor ]]) !!}
            <div class="form-row">
                <div class="col-md">
                    {{ Form::bsDate('date', Carbon\Carbon::now(), [ 'required' ], '') }}
                </div>
                <div class="col-md-auto">
                    {{ Form::bsSelect('currency', $currencies, Config::get('fundraising.base_currency'), [ 'required', 'id' => 'currency' ], '') }}
                </div>
                <div class="col-md">
                    {{ Form::bsNumber('amount', null, [ 'required', 'placeholder' => __('app.amount'), 'step' => 'any', 'id' => 'amount' ], '') }}
                </div>
            </div>
            <div class="form-row">
                <div class="col-md">
                    {{ Form::bsText('channel', null, [ 'required', 'placeholder' => __('fundraising.channel'), 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($channels)) ], '') }}
                </div>
                <div class="col-md">
                    {{ Form::bsText('purpose', null, [ 'placeholder' => __('fundraising.purpose') ], '') }}
                </div>
                <div class="col-md">
                    {{ Form::bsText('reference', null, [ 'placeholder' => __('fundraising.reference') ], '') }}
                </div>
            </div>
            <p>
                {{ Form::bsSubmitButton(__('app.add')) }}
            </p>
        {!! Form::close() !!}
    </div>
</div>