<div class="card mb-4">
    <div class="card-header">
        @lang('fundraising.donors')
        <a class="pull-right" href="{{ route('fundraising.donors.index')  }}">@lang('app.manage')</a>
    </div>
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
</div>
