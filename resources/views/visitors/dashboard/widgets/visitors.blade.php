@extends('dashboard.widgets.base')

@section('widget-title', __('visitors.visitors'))

@section('widget-content')
    <div class="card-body pb-2">
        <p>
            @lang('visitors.we_have_n_people_visiting_and_m_people_total_today', [
                'current' => $current_visitors,
                'total' => $todays_visitors
            ])
        </p>
    </div>
@endsection
