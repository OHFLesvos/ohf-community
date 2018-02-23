@allowed('view-reports')
    <div class="card mb-4">
        <div class="card-header">
            Reports
            <a class="pull-right" href="{{ route('reporting.index')  }}">More reports</a>
        </div>
        <div class="card-body">
            @foreach(Config::get('reporting.reports') as $report)
                @if($report['featured'])
                    @allowed($report['gate'])
                        <a href="{{ route($report['route']) }}">{{ $report['name'] }}</a><br>
                    @endallowed
                @endif
            @endforeach                    
        </div>
    </div>
@endallowed