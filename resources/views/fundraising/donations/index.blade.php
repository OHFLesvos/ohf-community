@extends('fundraising.layouts.donors-donations')

@section('title', __('fundraising.donation_management'))

@section('wrapped-content')

    <div id="fundraising-app">
        @php
            $fields = [
                [
                    'key' => 'date',
                    'label' => __('app.date'),
                    'class' => 'fit',
                    'sortable' => true,
                ],
                [
                    'key' => 'amount',
                    'label' => __('app.amount'),
                    'class' => 'text-right fit',
                    'sortable' => true,
                ],
                [
                    'key' => 'donor',
                    'label' => __('fundraising.donor'),
                    'sortable' => false,
                ],
                [
                    'key' => 'channel',
                    'label' => __('fundraising.channel'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => false,
                ],
                [
                    'key' => 'purpose',
                    'label' => __('fundraising.purpose'),
                    'sortable' => false,
                ],
                [
                    'key' => 'reference',
                    'label' => __('fundraising.reference'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => false,
                ],
                [
                    'key' => 'in_name_of',
                    'label' => __('fundraising.in_name_of'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => true,
                ],
                [
                    'key' => 'created_at',
                    'label' => __('app.registered'),
                    'class' => 'd-none d-sm-table-cell fit',
                    'sortable' => true,
                ],
                [
                    'key' => 'thanked',
                    'label' => __('fundraising.thanked'),
                    'class' => 'fit',
                    'sortable' => false,
                ],
            ];
        @endphp
        <donations-table
            id="donationsTable"
            :fields='@json($fields)'
            api-url="{{ route('api.fundraising.donations.index') }}"
            default-sort-by="created_at"
            default-sort-desc
            empty-text="@lang('fundraising.no_donations_found')"
            filter-placeholder="@lang('app.search_ellipsis')"
            :items-per-page="100"
            loading-label="@lang('app.loading')"
        ></donations-table>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
