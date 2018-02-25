<div class="card mb-4">
    <div class="card-header">
        @lang('donations.donors')
        <a class="pull-right" href="{{ route('donors.index')  }}">@lang('app.manage')</a>
    </div>
    <div class="card-body pb-2">
        <p>
            @lang('donations.donors_in_db', [ 'num_donors' => $num_donors ])<br>
            @isset($latest_donor)
                @lang('donations.newest_donor_is', [ 'link' => route('donors.show', $latest_donor), 'name' => $latest_donor->name ])
            @endisset
        </p>
    </div>
</div>
