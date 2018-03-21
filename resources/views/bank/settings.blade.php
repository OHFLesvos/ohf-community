@extends('layouts.app')

@section('title', __('app.settings'))

@section('content')

    {!! Form::open(['route' => ['bank.updateSettings']]) !!}

        <div class="card mb-4">
            <div class="card-header">@lang('people.display_settings')</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsNumber('people_results_per_page', $people_results_per_page, [ 'min' => 1 ], 'Number of results per page') }}
                    </div>
                </div>
            </div>
        </div>

		<div class="card mb-4">
			<div class="card-header">@lang('people.frequent_visitors')</div>
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
						<div class="form-group">
							<p>Persons are marked as frequent visitor <span class="text-warning" title="Frequent visitor">@icon(star)</span> if they visit the bank at least <em>x</em> times during the last <em>y</em> weeks.</p>
							{{ Form::bsNumber('frequent_visitor_weeks', $frequent_visitor_weeks, [ 'min' => 1 ], 'Number of weeks') }}
							{{ Form::bsNumber('frequent_visitor_threshold', $frequent_visitor_threshold, [ 'min' => 1 ], 'Minimum number of visits') }}
						</div>
					</div>
				</div>
				<div class="text-muted">Current settings: {{ $current_num_frequent_visitors }} persons affected, 
					out of {{ $current_num_people }} ({{ round($current_num_frequent_visitors/$current_num_people * 100) }} %)</div>
			</div>
		</div>

		<p>
			{{ Form::bsSubmitButton(__('app.update')) }}
		</p>

    {!! Form::close() !!}
    
@endsection
