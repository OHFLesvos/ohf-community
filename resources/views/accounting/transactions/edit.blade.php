@extends('layouts.app', ['wide_layout' => false])

@section('title', __('app.edit_transaction_number', ['number' => $transaction->receipt_no]))

@section('content')
    {!! Form::model($transaction, ['route' => ['accounting.transactions.update', $transaction], 'method' => 'put', 'files' => true]) !!}
        <div class="form-row">
            <div class="col-sm-auto">
                {{ Form::bsNumber('receipt_no', null, [ 'required', 'step' => '1', 'min' => 1 ], __('app.receipt_no')) }}
            </div>
            <div class="col-sm-auto">
                {{ Form::bsDate('date', null, [ 'required' ], __('app.date')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-auto pb-3">
            {{ Form::bsRadioInlineList('type', [ 'income' => __('app.income'), 'spending' => __('app.spending') ], 'spending', __('app.type')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsNumber('amount', null, [ 'required', 'step' => 'any', 'min' => 0], __('app.amount'), __('app.write_decimal_point_as_comma')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsNumber('fees', null, [ 'step' => 'any', 'min' => 0], __('app.transaction_fees'), __('app.write_decimal_point_as_comma')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('attendee', null, [ 'list' => $attendees ], __('app.attendee')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm">
                @if($fixed_categories)
                    {{ Form::bsSelect('category', collect($categories)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'required', 'placeholder' => '- ' . __('app.category') . ' -', 'error' => ! in_array($transaction->category, $categories) ? __('app.invalid_category_x', ['category' => $transaction->category]) : null ], __('app.category')) }}
                @else
                    {{ Form::bsText('category', null, [ 'required', 'list' => $categories ], __('app.category')) }}
                @endif
            </div>
            @if($secondary_categories !== null)
                <div class="col-sm">
                    @if($fixed_secondary_categories)
                        {{ Form::bsSelect('secondary_category', collect($secondary_categories)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.secondary_category') . ' -', 'error' => $transaction->secondary_category != null && ! in_array($transaction->secondary_category, $secondary_categories) ? __('app.invalid_secondary_category_x', ['secondary_category' => $transaction->secondary_category]) : null ], __('app.secondary_category')) }}
                    @else
                        {{ Form::bsText('secondary_category', null, [ 'list' => $secondary_categories ], __('app.secondary_category')) }}
                    @endif
                </div>
            @endif
            <div class="col-sm">
                @if($fixed_projects)
                    {{ Form::bsSelect('project', collect($projects)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.project') . ' -', 'error' => $transaction->project != null && ! in_array($transaction->project, $projects) ? __('app.invalid_project_x', ['project' => $transaction->project]) : null ], __('app.project')) }}
                @else
                    {{ Form::bsText('project', null, [ 'list' => $projects ], __('app.project')) }}
                @endif
            </div>
        </div>
        <div class="form-row">
            @if($locations !== null)
                <div class="col-sm">
                    @if($fixed_locations)
                        {{ Form::bsSelect('location', collect($locations)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.location') . ' -', 'error' => $transaction->location != null && ! in_array($transaction->location, $locations) ? __('app.invalid_location_x', ['location' => $transaction->location]) : null ], __('app.location')) }}
                    @else
                        {{ Form::bsText('location', null, [ 'list' => $locations ], __('app.location')) }}
                    @endif
                </div>
            @endif
            @if($cost_centers !== null)
                <div class="col-sm">
                    @if($fixed_cost_centers)
                        {{ Form::bsSelect('cost_center', collect($cost_centers)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('app.cost_center') . ' -', 'error' => $transaction->cost_center != null && ! in_array($transaction->cost_center, $cost_centers) ? __('app.invalid_cost_center_x', ['cost_center' => $transaction->cost_center]) : null ], __('app.cost_center')) }}
                    @else
                        {{ Form::bsText('cost_center', null, [ 'list' => $cost_centers ], __('app.cost_center')) }}
                    @endif
                </div>
            @endif
        </div>
        <div class="form-row">
            <div class="col-sm">
                {{ Form::bsText('description', null, [ 'required' ], __('app.description')) }}
            </div>
            @if($suppliers->count() > 0)
                <div class="col-sm">
                    {{ Form::bsSelect('supplier_id', collect($suppliers)->mapWithKeys(fn ($e) => [$e->id => $e->name . ($e->category !== null ? ' (' . $e->category . ')' : '')]), null, [ 'placeholder' => '- ' . __('app.supplier') . ' -' ], __('app.supplier')) }}
                </div>
            @endisset
        </div>
        <div class="form-row">
            <div class="col-sm">
                <label>@lang('app.receipt')</label>
                @if(! empty($transaction->receipt_pictures))
                    <div class="form-row">
                        @foreach($transaction->receipt_pictures as $picture)
                            <div class="col-auto">
                                <x-thumbnail :size="config('accounting.thumbnail_size')">
                                    @if(Str::startsWith(Storage::mimeType($picture), 'image/'))
                                        @if(Storage::exists(thumb_path($picture)))
                                            {{ Storage::url(thumb_path($picture)) }}
                                        @else
                                            {{ Storage::url($picture) }}
                                        @endif
                                    @else
                                       {{ Storage::url(thumb_path($picture, 'jpeg')) }}
                                    @endif
                                </x-thumbnail>
                                {{ Form::bsCheckbox('remove_receipt_picture[]', $picture, null, __('app.remove'), 'remove_receipt_picture'.$loop->index) }}<br>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="form-row">
                    <div class="col-sm">
                        {{ Form::bsFile('receipt_picture[]', [ 'accept' => 'image/*,application/pdf', 'multiple' ], __('app.add_picture_of_receipt')) }}
                    </div>
                </div>
            </div>
            <div class="col-sm">
                {{ Form::bsText('remarks', null, [  ], __('app.remarks')) }}
            </div>
        </div>
        <p>
            <x-form.bs-submit-button :label="__('app.update')"/>
        </p>
    {!! Form::close() !!}
@endsection
