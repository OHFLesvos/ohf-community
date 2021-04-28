@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Edit transaction #:number', ['number' => $transaction->receipt_no]))

@section('content')
    {!! Form::model($transaction, ['route' => ['accounting.transactions.update', $transaction], 'method' => 'put', 'files' => true]) !!}
        <div class="form-row">
            <div class="col-sm-auto">
                {{ Form::bsNumber('receipt_no', null, [ 'required', 'step' => '1', 'min' => 1 ], __('Receipt No.')) }}
            </div>
            <div class="col-sm-auto">
                {{ Form::bsDate('date', null, [ 'required' ], __('Date')) }}
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
                {{ Form::bsSelect('category_id', $categories, null, [ 'required', 'placeholder' => '- ' . __('Category') . ' -'], __('Category')) }}
            </div>
            @if($secondary_categories !== null)
                <div class="col-sm">
                    @if($fixed_secondary_categories)
                        {{ Form::bsSelect('secondary_category', collect($secondary_categories)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('Secondary Category') . ' -', 'error' => $transaction->secondary_category != null && ! in_array($transaction->secondary_category, $secondary_categories) ? __("Invalid secondary category ':secondary_category'", ['secondary_category' => $transaction->secondary_category]) : null ], __('Secondary Category')) }}
                    @else
                        {{ Form::bsText('secondary_category', null, [ 'list' => $secondary_categories ], __('Secondary Category')) }}
                    @endif
                </div>
            @endif
            <div class="col-sm">
                @if($fixed_projects)
                    {{ Form::bsSelect('project', collect($projects)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('Project') . ' -', 'error' => $transaction->project != null && ! in_array($transaction->project, $projects) ? __("Invalid project ':project'", ['project' => $transaction->project]) : null ], __('Project')) }}
                @else
                    {{ Form::bsText('project', null, [ 'list' => $projects ], __('Project')) }}
                @endif
            </div>
        </div>
        <div class="form-row">
            @if($locations !== null)
                <div class="col-sm">
                    @if($fixed_locations)
                        {{ Form::bsSelect('location', collect($locations)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('Location') . ' -', 'error' => $transaction->location != null && ! in_array($transaction->location, $locations) ? __("Invalid location ':location'", ['location' => $transaction->location]) : null ], __('Location')) }}
                    @else
                        {{ Form::bsText('location', null, [ 'list' => $locations ], __('Location')) }}
                    @endif
                </div>
            @endif
            @if($cost_centers !== null)
                <div class="col-sm">
                    @if($fixed_cost_centers)
                        {{ Form::bsSelect('cost_center', collect($cost_centers)->mapWithKeys(fn ($e) => [ $e => $e ]), null, [ 'placeholder' => '- ' . __('Cost Center') . ' -', 'error' => $transaction->cost_center != null && ! in_array($transaction->cost_center, $cost_centers) ? __("Invalid cost center ':cost_center'", ['cost_center' => $transaction->cost_center]) : null ], __('Cost Center')) }}
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
            @endisset
        </div>
        <div class="form-row">
            <div class="col-sm">
                <label>@lang('Receipt')</label>
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
                                {{ Form::bsCheckbox('remove_receipt_picture[]', $picture, null, __('Remove'), 'remove_receipt_picture'.$loop->index) }}<br>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="form-row">
                    <div class="col-sm">
                        {{ Form::bsFile('receipt_picture[]', [ 'accept' => 'image/*,application/pdf', 'multiple' ], __('Add picture of receipt')) }}
                    </div>
                </div>
            </div>
            <div class="col-sm">
                {{ Form::bsText('remarks', null, [  ], __('Remarks')) }}
            </div>
        </div>
        <p>
            <x-form.bs-submit-button :label="__('Update')"/>
        </p>
    {!! Form::close() !!}
@endsection
