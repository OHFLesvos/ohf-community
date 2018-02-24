<div class="card mb-4">
    <div class="card-header">
        People
        <a class="pull-right" href="{{ route('people.index')  }}">@lang('app.manage')</a>
    </div>
    <div class="card-body pb-2">
        <p>
            There are <strong>{{ $num_people }}</strong> people registered in our database. 
            @if($num_people_added_today > 0)(<strong>{{ $num_people_added_today }}</strong> new today)@endif
        </p>
    </div>
</div>
