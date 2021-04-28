@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Register new transaction'))

@section('content')
    {!! Form::open(['route' => ['accounting.transactions.store', $wallet], 'files' => true]) !!}
        <div class="form-row">
            <div class="col-sm-auto">
                {{ Form::bsNumber('receipt_no', $wallet->nextFreeReceiptNumber, [ 'required', 'step' => '1', 'min' => 1 ], __('Receipt No.')) }}
            </div>
            <div class="col-sm-auto">
                {{ Form::bsDate('date', Carbon\Carbon::today(), [ 'required' ], __('Date')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-auto pb-3">
                {{ Form::bsRadioInlineList('type', [ 'income' => __('Income'), 'spending' => __('Spending') ], 'spending', __('Type')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsNumber('amount', null, [ 'required', 'step' => 'any', 'min' => 0], __('Amount'), __('Write decimal point as comma (,)')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsNumber('fees', null, [ 'step' => 'any', 'min' => 0], __('Transaction fees'), __('Write decimal point as comma (,)')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('attendee', null, [ 'list' => $attendees ], __('Attendee')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm">
                {{ Form::bsSelect('category_id', $categories, null, [ 'required', 'placeholder' => '- ' . __('Category') . ' -' ], __('Category')) }}
            </div>
            @if($secondary_categories !== null)
                <div class="col-sm">
                    @if($fixed_secondary_categories)
                        {{ Form::bsSelect('secondary_category', collect($secondary_categories)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('Secondary Category') . ' -' ], __('Secondary Category')) }}
                    @else
                        {{ Form::bsText('secondary_category', null, [ 'required', 'list' => $secondary_categories ], __('Secondary Category')) }}
                    @endif
                </div>
            @endif
            <div class="col-sm">
                {{ Form::bsSelect('project_id', $projects, null, [ 'placeholder' => '- ' . __('Project') . ' -' ], __('Project')) }}
            </div>
        </div>
        <div class="form-row">
            @if($locations !== null)
                <div class="col-sm">
                    @if($fixed_locations)
                        {{ Form::bsSelect('location', collect($locations)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('Location') . ' -' ], __('Location')) }}
                    @else
                        {{ Form::bsText('location', null, [ 'list' => $locations ], __('Location')) }}
                    @endif
                </div>
            @endif
            @if($cost_centers !== null)
                <div class="col-sm">
                    @if($fixed_cost_centers)
                        {{ Form::bsSelect('cost_center', collect($cost_centers)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('Cost Center') . ' -' ], __('Cost Center')) }}
                    @else
                        {{ Form::bsText('cost_center', null, [ 'list' => $cost_centers ], __('Cost Center')) }}
                    @endif
                </div>
            @endif
        </div>
        <div class="form-row">
            <div class="col-sm">
                {{ Form::bsText('description', null, [ 'required' ], __('Description')) }}
            </div>
            @if($suppliers->count() > 0)
                <div class="col-sm">
                    {{ Form::bsSelect('supplier_id', collect($suppliers)->mapWithKeys(fn ($e) => [$e->id => $e->name . ($e->category !== null ? ' (' . $e->category . ')' : '')]), null, [ 'placeholder' => '- ' . __('Supplier') . ' -' ], __('Supplier')) }}
                </div>
            @endif
        </div>
        <div class="form-row">
            <div class="col-sm">
                <label>@lang('Receipt')</label>
                {{ Form::bsFile('receipt_picture[]', [ 'accept' => 'image/*,application/pdf', 'multiple' ], __('Choose picture of receipt')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('remarks', null, [  ], __('Remarks')) }}
            </div>
        </div>
        <p>
            <x-form.bs-submit-button :label="__('Add')"/>
            <x-form.bs-submit-button
                :label="__('Save and continue')"
                icon="arrow-right"
                name="submit"
                value="save_and_continue"
                class="btn-secondary"/>
        </p>
    {!! Form::close() !!}

@endsection

@push('footer')
    <script>
        $(function () {
            $('input[name="date"]').on('change', function () {
                $('input[name="amount"]').focus();
            });
        });
    </script>
@endpush
