@extends('layouts.app')

@section('title', __('Accounting'))

@section('content')

    <div class="d-flex justify-content-between align-items-center">
        <div class="mb-3">
            <x-icon icon="wallet"/>
            <span class="d-none d-sm-inline">
                @if($has_multiple_wallets)
                    {{ $wallet->name }}:
                @else
                    @lang('Wallet'):
                @endif
            </span>
            <u>{{ number_format($wallet->amount, 2) }}</u>
            @if($has_multiple_wallets)
                <a href="{{ route('accounting.index') }}" class="d-none d-sm-inline btn btn-sm btn-primary ml-2">
                    @lang('Change')
                </a>
                <a href="{{ route('accounting.index') }}" class="d-inline d-sm-none btn btn-sm">
                    <x-icon icon="folder-open"/>
                </a>
            @endif
        </div>
        <div class="text-right">
            @if(count($filter) > 0)
                <a href="{{ route('accounting.transactions.index', $wallet) }}?reset_filter=1" class="btn btn-sm btn-primary mb-3"><x-icon icon="eraser"/> @lang('Reset filter')</a>
            @endif
            <button type="button" class="btn btn-sm btn-secondary mb-3" data-toggle="modal" data-target="#filterModal">
                <x-icon icon="search"/>
                @if(count($filter) > 0 ?)
                    @lang('Edit filter')
                @else
                    @lang('Filter results')
                @endif
            </button>
        </div>
    </div>

    @if(! $transactions->isEmpty())
        <div class="table-responsive">
            <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <th colspan="2" class="fit text-center @if(isset($filter['receipt_no']) || isset($filter['no_receipt'])) text-info @endif"><span class="d-none d-sm-inline">@lang('Receipt') </span>#</th>
                        <th class="fit @if(isset($filter['date_start']) || isset($filter['date_end']) || isset($filter['month'])) text-info @endisset">@lang('Date')</th>
                        <th class="fit d-table-cell d-sm-none text-right">@lang('Amount')</th>
                        <th class="fit d-none d-sm-table-cell text-right @if(isset($filter['type']) && $filter['type']=='income') text-info @endisset">@lang('Income')</th>
                        <th class="fit d-none d-sm-table-cell text-right @if(isset($filter['type']) && $filter['type']=='spending') text-info @endisset">@lang('Spending')</th>
                        @if($intermediate_balances !== null)
                            <th class="fit text-right">@lang('Intermediate balance')</th>
                        @endif
                        <th class="@isset($filter['category_id']) text-info @endisset">@lang('Category')</th>
                        @if($secondary_categories !== null)
                            <th class="@isset($filter['secondary_category']) text-info @endisset">@lang('Secondary Category')</th>
                        @endif
                        <th class="@isset($filter['project']) text-info @endisset">@lang('Project')</th>
                        @if($locations !== null)
                            <th class="@isset($filter['location']) text-info @endisset">@lang('Location')</th>
                        @endif
                        @if($cost_centers !== null)
                            <th class="@isset($filter['cost_center']) text-info @endisset">@lang('Cost Center')</th>
                        @endif
                        <th class="d-none d-sm-table-cell @isset($filter['description']) text-info @endisset">@lang('Description')</th>
                        @if($has_suppliers)
                            <th class="d-none d-sm-table-cell @isset($filter['supplier']) text-info @endisset">@lang('Supplier')</th>
                        @endif
                        <th class="d-none d-sm-table-cell @isset($filter['attendee']) text-info @endisset">@lang('Attendee')</th>
                        <th class="fit d-none d-md-table-cell @isset($filter['today']) text-info @endisset">@lang('Registered')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="fit text-center cursor-pointer @if(empty($transaction->receipt_pictures) && isset($transaction->receipt_no)) table-warning receipt-picture-missing @else table-success @endif" data-transaction-id="{{ $transaction->id }}">
                                @isset($transaction->receipt_no)
                                    @if(filled($transaction->receipt_pictures))
                                        @php
                                            $urls = collect($transaction->receipt_pictures)
                                                ->filter(fn ($picture) => Storage::exists($picture))
                                                ->map(fn ($picture) => [ 'url' => Storage::url($picture), 'image' => Str::startsWith(Storage::mimeType($picture), 'image/')]);
                                            if ($urls->filter(fn ($data) => $data['image'])->isNotEmpty()) {
                                                $icon = $urls->count() > 1 ? 'images' : 'image';
                                            } else {
                                                $icon = 'file';
                                            }
                                        @endphp
                                        @if(filled($urls))
                                            <x-icon :icon="$icon" :data-urls='$urls->toJson()' class="lightbox" />
                                        @endif
                                    @else
                                        <x-icon icon="upload" />
                                    @endif
                                @endisset
                            </td>
                            <td class="fit text-right" >
                                {{ $transaction->receipt_no }}
                            </td>
                            <td class="fit">
                                <a href="{{ route('accounting.transactions.show', $transaction) }}"
                                    data-url="{{ route('accounting.transactions.snippet', $transaction) }}"
                                    @can('update', $transaction) data-edit-url="{{ route('accounting.transactions.edit', $transaction) }}"@endcan
                                    class="details-link">
                                    {{ $transaction->date }}
                                </a>
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
                            <td>{{ $transaction->project }}</td>
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
                @if(count($filter) > 0)
                    @php
                        $sum_income = $transactions->where('type', 'income')->sum('amount');
                        $sum_spending = $transactions->where('type', 'spending')->sum('amount');
                    @endphp
                    @if($sum_income > 0 || $sum_spending > 0)
                        <tr>
                            <td colspan="2" rowspan="2" class="align-middle">@lang('Total')</td>
                            <td class="text-right d-none d-sm-table-cell">
                                <u class="text-success">{{ number_format($sum_income, 2) }}</u>
                            </td>
                            <td class="text-right d-none d-sm-table-cell">
                                <u class="text-danger">{{ number_format($sum_spending, 2) }}</u>
                            </td>
                            <td class="text-right d-table-cell d-sm-none">
                                @if($sum_income > 0)<u class="text-success">{{ number_format($sum_income, 2) }}</u><br>@endif
                                @if($sum_spending > 0)<u class="text-danger">{{ number_format($sum_spending, 2) }}</u>@endif
                                <u>{{ number_format($sum_income - $sum_spending, 2) }}</u>
                            </td>
                            <td colspan="6" rowspan="2"></td>
                        </tr>
                        <tr class="d-none d-sm-table-row">
                            <td colspan="2" class="text-center"><u>{{ number_format($sum_income - $sum_spending, 2) }}</u></td>
                        </tr>
                    @endif
                @endif
            </table>
        </div>
        <div style="overflow-x: auto">
            {{ $transactions->appends($filter)->links() }}
        </div>
        @foreach ($transactions->filter(fn ($e) => $e->receipt_no != null && empty($e->receipt_pictures)) as $transaction)
            <form action="{{ route('api.accounting.transactions.updateReceipt', $transaction) }}" method="post" enctype="multipart/form-data" class="d-none upload-receipt-form" id="receipt_upload_{{ $transaction->id }}">
                {{ Form::file('img[]', [ 'accept' => 'image/*,application/pdf', 'multiple', 'class' => 'd-none' ]) }}
            </form>
        @endforeach
    @else
        <x-alert type="info">
            @lang('No transactions found.')
        </x-alert>
    @endif
@endsection

@push('footer')
    <script>
        var selectFile = function (evt) {
            var tr_id = $(evt.target).data('transaction-id');
            $('#receipt_upload_' + tr_id).find('input[type=file]').click();
        }
        $(function () {
            $('.receipt-picture-missing').on('click', selectFile);
            $('.upload-receipt-form input[type="file"]').on('change', function () {
                $(this).parents('form').submit();
            });
            $('.upload-receipt-form').on('submit', function (e) {
                e.preventDefault();
                var tr_id = $(this).attr('id').substr('#receipt_upload_'.length - 1);
                var td = $('.receipt-picture-missing[data-transaction-id="' + tr_id + '"]');
                var icon = td.children('i');
                td.removeClass('table-warning')
                    .addClass('table-info')
                    .removeClass('cursor-pointer')
                    .off('click');
                icon.removeClass('fa-upload')
                    .addClass('fa-spin fa-spinner')
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function () {
                        td.removeClass('table-info receipt-picture-missing')
                            .addClass('table-success');
                        icon.removeClass('fa-spin fa-spinner')
                            .addClass('fa-check');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        td.removeClass('table-info')
                            .addClass('table-warning')
                            .addClass('cursor-pointer')
                            .on('click', selectFile);
                        icon.removeClass('fa-spin fa-spinner')
                            .addClass('fa-upload');
                        var message;
                        if (jqXHR.responseJSON.message) {
                            if (jqXHR.responseJSON.errors) {
                                message = "";
                                var errors = jqXHR.responseJSON.errors;
                                Object.keys(errors).forEach(function (key) {
                                    message += errors[key] + "\n";
                                });
                            } else {
                                message = jqXHR.responseJSON.message;
                            }
                        } else {
                            message = textStatus + ': ' + jqXHR.responseText;
                        }
                        alert(message);
                    }
                });
            });

            $('.details-link').on('click', function (e) {
                e.preventDefault();
                var container = $('#detailsModal');
                var edit_url =  $(this).data('edit-url');
                container.modal('show');
                container.find('.modal-header')
                    .hide();
                container.find('.modal-footer')
                    .hide();
                container.find('.modal-body')
                    .removeClass('pb-0')
                    .removeClass('p-0')
                    .html('<div class="text-center p-4"><i class="fa fa-spin fa-spinner"></i> Loading...</div>');
                $.get($(this).data('url'), function (result) {
                    container.find('.modal-header')
                        .show();
                    container.find('.modal-body')
                        .addClass('p-0')
                        .html(result);
                    var footer_html = '';
                    if (edit_url) {
                        footer_html += '<a href="' + edit_url +'" class="btn btn-secondary"><i class="fa fa-edit"></i> Edit</a>';
                    }
                    if (footer_html.length > 0) {
                        container.find('.modal-footer')
                            .html(footer_html)
                            .show();
                    }
                    @include('accounting.transactions.controlled')
                    refreshFsLightbox()
                });
            });

            $('.lightbox').each(function(){
                var data = $(this).data('urls');
                const lightbox = new FsLightbox();
                var images = data.filter(d => d.image)
                lightbox.props.sources = images.map(i => i.url);
                $(this).parents('td').on('click', function() {
                    if (images.length > 0) {
                        lightbox.open()
                    } else {
                        window.open(data[0].url, '_blank');
                    }
                 });
            })
        });
    </script>

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
                    @if($fixed_projects)
                        {{ Form::bsSelect('filter[project]', collect($projects)->mapWithKeys(fn ($e) => [ $e => $e ]), $filter['project'] ?? null, [ 'placeholder' => '- ' . __('Project') . ' -' ], __('Project')) }}
                    @else
                        {{ Form::bsText('filter[project]', $filter['project'] ?? null, [ 'list' => $projects ], __('Project')) }}
                    @endif
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
                    <a href="{{ route('accounting.transactions.index', $wallet) }}?reset_filter=1" class="btn btn-secondary" tabindex="-1"><x-icon icon="eraser"/> @lang('Reset filter')</a>
                @endif
                <x-form.bs-submit-button :label="__('Update')" icon="search"/>
            @endslot
        @endcomponent
    {!! Form::close() !!}

    @component('components.modal', [ 'id' => 'detailsModal' ])
        @slot('title', 'Transaction details')
        @slot('footer')@endslot
    @endcomponent
@endpush
