<div class="card mb-4">
    <div class="card-header">
        @lang('app.reports')
        <a class="pull-right" href="{{ route('reporting.index')  }}">@lang('app.more_reports')</a>
    </div>
    <div class="card-body">
        @foreach($reports as $report)
            <a href="{{ $report->url }}">
                {{ $report->name }}
            </a><br>
        @endforeach                    
    </div>
</div>
