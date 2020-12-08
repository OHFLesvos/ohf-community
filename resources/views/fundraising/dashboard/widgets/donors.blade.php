@extends('dashboard.widgets.base')

@section('widget-title', __('fundraising.donation_management'))

@section('widget-content')
    <div class="card-body pb-2">
        <p>
            @lang('fundraising.donors_in_db', [ 'num_donors' => $num_donors ])<br>
            @lang('fundraising.donations_in_db', [
                'num_month' => $num_donations_month,
                'num_year' => $num_donations_year,
                'num_total' => $num_donations_total
            ])<br>
        </p>
    </div>
@endsection
