<div class="card mb-4">
    <div class="card-header">
        @lang('donations.register_new_donation')
        <a class="pull-right" href="{{ route('donations.create', $donor)  }}">@icon(arrows-alt)</a>
    </div>
    <div class="card-body pb-0">
        {!! Form::open(['route' => ['donations.store', $donor ]]) !!}
            <div class="form-row">
                <div class="col-md">
                    {{ Form::bsDate('date', Carbon\Carbon::now(), [ 'required' ], '') }}
                </div>
                <div class="col-md-auto">
                    {{ Form::bsSelect('currency', $currencies, Config::get('donations.base_currency'), [ 'required', 'id' => 'currency' ], '') }}
                </div>
                <div class="col-md">
                    {{ Form::bsNumber('amount', null, [ 'required', 'placeholder' => __('donations.amount'), 'step' => 'any', 'id' => 'amount' ], '') }}
                </div>
                <div class="col-md">
                    {{ Form::bsText('origin', null, [ 'required', 'placeholder' => __('donations.origin'), 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($origins)) ], '') }}
                </div>
            </div>
            <p>
                {{ Form::bsSubmitButton(__('app.add')) }}
            </p>
        {!! Form::close() !!}
    </div>
</div>