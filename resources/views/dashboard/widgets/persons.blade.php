<div class="card mb-4">
    <div class="card-header">
        @lang('people.people')
        <a class="pull-right" href="{{ route('people.index')  }}">@lang('app.manage')</a>
    </div>
    <div class="card-body pb-2">
        <p>
            @lang('people.there_are_n_people_registered', [ 'num_people' => $num_people ])@if($num_people_added_today > 0)(@lang('people.n_new_today', [ 'num_people_added_today' => $num_people_added_today ]))@endif.
        </p>
    </div>
</div>
