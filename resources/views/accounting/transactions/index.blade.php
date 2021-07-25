@extends('layouts.app')

@section('title', __('Accounting'))

@section('content')

    <div id="app">
        <x-spinner />
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div class="text-right">
            @if(count($filter) > 0)
                <a href="{{ route('accounting.transactions.index', $wallet) }}?reset_filter=1" class="btn btn-sm btn-primary mb-3"><x-icon icon="eraser"/> {{ __('Reset filter') }}</a>
            @endif
            <button type="button" class="btn btn-sm btn-secondary mb-3" data-toggle="modal" data-target="#filterModal">
                <x-icon icon="search"/>
                @if(count($filter) > 0)
                    {{ __('Edit filter') }}
                @else
                    {{ __('Filter results') }}
                @endif
            </button>
        </div>
    </div>
@endsection

@push('footer')
    {!! Form::open(['route' => ['accounting.transactions.index', $wallet ], 'method' => 'get']) !!}
        @component('components.modal', [ 'id' => 'filterModal' ])
            @slot('title', __('Filter'))

            <div class="form-row">
                <div class="col-sm mb-3">
                    {{ Form::bsRadioList('filter[type]', [ 'income' => __('Income'), 'spending' => __('Spending'), null => __('Any') ], $filter['type'] ?? null, __('Type')) }}
                </div>
                <div class="col-sm mb-3">
                    {{ Form::bsRadioList('filter[controlled]', [ 'yes' => __('Yes'), 'no' => __('No'), null => __('Any') ], $filter['controlled'] ?? null, __('Controlled')) }}
                </div>
                <div class="col-sm">
                    {{ Form::bsNumber('filter[receipt_no]', $filter['receipt_no'] ?? null, [ 'min' => 1 ], __('Receipt') . ' #') }}
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm">
                    {{ Form::bsDate('filter[date_start]', $filter['date_start'] ?? null, [], __('From')) }}
                </div>
                <div class="col-sm">
                    {{ Form::bsDate('filter[date_end]', $filter['date_end'] ?? null, [], __('To')) }}
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm">
                    {{ Form::bsSelect('filter[category_id]', $categories, $filter['category_id'] ?? null, [ 'placeholder' => '- ' . __('Category') . ' -' ], __('Category')) }}
                </div>
                @if($secondary_categories !== null)
                    <div class="col-sm">
                        @if($fixed_secondary_categories)
                            {{ Form::bsSelect('filter[secondary_category]', collect($secondary_categories)->mapWithKeys(fn ($e) => [ $e => $e ]), $filter['secondary_category'] ?? null, [ 'placeholder' => '- ' . __('Secondary Category') . ' -' ], __('Category')) }}
                        @else
                            {{ Form::bsText('filter[secondary_category]', $filter['secondary_category'] ?? null, [ 'list' => $secondary_categories ], __('Secondary Category')) }}
                        @endif
                    </div>
                @endif
                <div class="col-sm">
                    {{ Form::bsSelect('filter[project_id]', $projects, $filter['project_id'] ?? null, [ 'placeholder' => '- ' . __('Project') . ' -' ], __('Project')) }}
                </div>
            </div>
            <div class="form-row">
                @if($locations !== null)
                    <div class="col-sm">
                        @if($fixed_locations)
                            {{ Form::bsSelect('filter[location]', collect($locations)->mapWithKeys(fn ($e) => [ $e => $e ]), $filter['location'] ?? null, [ 'placeholder' => '- ' . __('Location') . ' -' ], __('Location')) }}
                        @else
                            {{ Form::bsText('filter[location]', $filter['location'] ?? null, [ 'list' => $locations ], __('Location')) }}
                        @endif
                    </div>
                @endif
                @if($cost_centers !== null)
                    <div class="col-sm">
                        @if($fixed_cost_centers)
                            {{ Form::bsSelect('filter[cost_center]', collect($cost_centers)->mapWithKeys(fn ($e) => [ $e => $e ]), $filter['cost_center'] ?? null, [ 'placeholder' => '- ' . __('Cost Center') . ' -' ], __('Cost Center')) }}
                        @else
                            {{ Form::bsText('filter[cost_center]', $filter['cost_center'] ?? null, [ 'list' => $cost_centers ], __('Cost Center')) }}
                        @endif
                    </div>
                @endif
            </div>
            <div class="form-row">
                <div class="col-sm">
                    {{ Form::bsText('filter[attendee]', $filter['attendee'] ?? null, [ 'list' => $attendees ], __('Attendee')) }}
                </div>
                <div class="col-sm">
                    {{ Form::bsText('filter[description]', $filter['description'] ?? null, [ ], __('Description')) }}
                </div>
            </div>
            <div class="form-row">
                @if($has_suppliers)
                    <div class="col-sm">
                        {{ Form::bsText('filter[supplier]', $filter['supplier'] ?? null, [ 'list' => $suppliers->pluck('name')->toArray(), 'autocomplete' => 'off' ], __('Supplier')) }}
                    </div>
                @endif
                <div class="col-sm">
                    @if($has_suppliers)
                    <br>
                    @endif
                    {{ Form::bsCheckbox('filter[today]', 1, $filter['today'] ?? false, __('Registered today')) }}
                    {{ Form::bsCheckbox('filter[no_receipt]', 1, $filter['no_receipt'] ?? false, __('No receipt')) }}
                </div>
            </div>
            <hr>

            @slot('footer')
                @if(count($filter) > 0)
                    <a href="{{ route('accounting.transactions.index', $wallet) }}?reset_filter=1" class="btn btn-secondary" tabindex="-1"><x-icon icon="eraser"/> {{ __('Reset filter') }}</a>
                @endif
                <x-form.bs-submit-button :label="__('Update')" icon="search"/>
            @endslot
        @endcomponent
    {!! Form::close() !!}
@endpush
