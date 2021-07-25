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

    @if(! $transactions->isEmpty())
        <div class="table-responsive">
            <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <th class="fit text-center @if(isset($filter['receipt_no']) || isset($filter['no_receipt'])) text-info @endif"><span class="d-none d-sm-inline">{{ __('Receipt') }} </span>#</th>
                        <th class="fit @if(isset($filter['date_start']) || isset($filter['date_end']) || isset($filter['month'])) text-info @endisset">{{ __('Date') }}</th>
                        <th class="fit d-table-cell d-sm-none text-right">{{ __('Amount') }}</th>
                        <th class="fit d-none d-sm-table-cell text-right @if(isset($filter['type']) && $filter['type']=='income') text-info @endisset">{{ __('Income') }}</th>
                        <th class="fit d-none d-sm-table-cell text-right @if(isset($filter['type']) && $filter['type']=='spending') text-info @endisset">{{ __('Spending') }}</th>
                        @if($intermediate_balances !== null)
                            <th class="fit text-right">{{ __('Intermediate balance') }}</th>
                        @endif
                        <th class="@isset($filter['category_id']) text-info @endisset">{{ __('Category') }}</th>
                        @if($secondary_categories !== null)
                            <th class="@isset($filter['secondary_category']) text-info @endisset">{{ __('Secondary Category') }}</th>
                        @endif
                        <th class="@isset($filter['project_id']) text-info @endisset">{{ __('Project') }}</th>
                        @if($locations !== null)
                            <th class="@isset($filter['location']) text-info @endisset">{{ __('Location') }}</th>
                        @endif
                        @if($cost_centers !== null)
                            <th class="@isset($filter['cost_center']) text-info @endisset">{{ __('Cost Center') }}</th>
                        @endif
                        <th class="d-none d-sm-table-cell @isset($filter['description']) text-info @endisset">{{ __('Description') }}</th>
                        @if($has_suppliers)
                            <th class="d-none d-sm-table-cell @isset($filter['supplier']) text-info @endisset">{{ __('Supplier') }}</th>
                        @endif
                        <th class="d-none d-sm-table-cell @isset($filter['attendee']) text-info @endisset">{{ __('Attendee') }}</th>
                        <th class="fit d-none d-md-table-cell @isset($filter['today']) text-info @endisset">{{ __('Registered') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="fit text-right" >
                                <a href="{{ route('accounting.transactions.show', $transaction) }}">
                                    {{ $transaction->receipt_no }}
                                </a>
                            </td>
                            <td class="fit">
                                {{ $transaction->date }}
                            </td>
                            <td class="fit d-table-cell d-sm-none text-right @if($transaction->type == 'income') text-success @elseif($transaction->type == 'spending') text-danger @endif">{{ number_format($transaction->amount, 2) }}</td>
                            <td class="fit d-none d-sm-table-cell text-right text-success">@if($transaction->type == 'income') {{ number_format($transaction->amount, 2) }}@endif</td>
                            <td class="fit d-none d-sm-table-cell text-right text-danger">@if($transaction->type == 'spending') {{ number_format($transaction->amount, 2) }}@endif</td>
                            @if($intermediate_balances !== null)
                                <td class="fit text-right">{{ number_format($intermediate_balances[$transaction->id], 2) }}</td>
                            @endif
                            <td>{{ $transaction->category->name }}</td>
                            @if($secondary_categories !== null)
                                <td>{{ $transaction->secondary_category }}</td>
                            @endif
                            <td>{{ optional($transaction->project)->name }}</td>
                            @if($locations !== null)
                                <td>{{ $transaction->location }}</td>
                            @endif
                            @if($cost_centers !== null)
                                <td>{{ $transaction->cost_center }}</td>
                            @endif
                            <td class="d-none d-sm-table-cell">{{ $transaction->description }}</td>
                            @if($has_suppliers)
                                <td class="d-none d-sm-table-cell">
                                    @isset($transaction->supplier)
                                        @can('view', $transaction->supplier)
                                            <a href="{{ route('accounting.suppliers.show', $transaction->supplier) }}">
                                                {{ $transaction->supplier->name }}
                                            </a>
                                        @else
                                            {{ $transaction->supplier->name }}
                                        @endcan
                                    @endisset
                                </td>
                            @endif
                            <td class="d-none d-sm-table-cell">{{ $transaction->attendee }}</td>
                            @php
                                $audit = $transaction->audits()->first();
                            @endphp
                            <td class="fit d-none d-md-table-cell">{{ $transaction->created_at }} @if(isset($audit) && isset($audit->getMetadata()['user_name']))({{ $audit->getMetadata()['user_name'] }})@endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="overflow-x: auto">
            {{ $transactions->appends($filter)->links() }}
        </div>
    @else
        <x-alert type="info">
            {{ __('No transactions found.') }}
        </x-alert>
    @endif
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
            <div class="form-row">
                <div class="col-sm-auto">
                    {{ Form::bsSelect('sortColumn', $sortColumns, $sortColumn, [], __('Order by')) }}
                </div>
                <div class="col-sm-auto mb-3">
                    {{ Form::bsRadioList('sortOrder', [ 'asc' => __('Ascending'), 'desc' => __('Descending') ], $sortOrder, __('Order')) }}
                </div>
            </div>

            @slot('footer')
                @if(count($filter) > 0)
                    <a href="{{ route('accounting.transactions.index', $wallet) }}?reset_filter=1" class="btn btn-secondary" tabindex="-1"><x-icon icon="eraser"/> {{ __('Reset filter') }}</a>
                @endif
                <x-form.bs-submit-button :label="__('Update')" icon="search"/>
            @endslot
        @endcomponent
    {!! Form::close() !!}
@endpush
