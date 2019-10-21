@extends('fundraising::layouts.donors-donations')

@section('title', __('fundraising::fundraising.donation_management'))

@section('wrapped-content')

    <div id="fundraising-app">
        @php
            $fields = [
                [
                    'key' => 'first_name',
                    'label' => __('app.first_name'),
                    'sortable' => true,
                ],
                [
                    'key' => 'last_name',
                    'label' => __('app.last_name'),
                    'sortable' => true,
                ],
                [
                    'key' => 'company',
                    'label' => __('app.company'),
                    'sortable' => true,
                ],
                [
                    'key' => 'street',
                    'label' => __('app.street'),
                    'class' => 'd-none d-sm-table-cell',
                ],
                [
                    'key' => 'zip',
                    'label' => __('app.zip'),
                    'class' => 'd-none d-sm-table-cell',
                ],
                [
                    'key' => 'city',
                    'label' => __('app.city'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => true,
                ],
                [
                    'key' => 'country',
                    'label' => __('app.country'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => true,
                ],
                [
                    'key' => 'email',
                    'label' => __('app.email'),
                    'class' => 'd-none d-sm-table-cell',
                ],
                [
                    'key' => 'phone',
                    'label' => __('app.phone'),
                    'class' => 'd-none d-sm-table-cell',
                    'type' => 'tel',
                ],
                [
                    'key' => 'language',
                    'label' => __('app.correspondence_language'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => true,
                ],
            ];
        @endphp
        <donors-table 
            id="donorsTable"
            :fields='@json($fields)'
            api-url="{{ route('api.fundraising.donors.index') }}"
            default-sort-by="first_name"
            empty-text="@lang('fundraising::fundraising.no_donors_found')"
            filter-placeholder="@lang('fundraising::fundraising.search_for_name_address_email_phone')..."
            :items-per-page="25"
            :tags='@json((object)$tags->toArray())'
        ></donors-table>
    </div>
	
@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
