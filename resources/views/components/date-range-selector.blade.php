{{ Form::open(['route' => $route, 'method' => 'get']) }}
    <div class="form-row">
        <div class="col">{{ $slot }}</div>
        <div class="col-auto">{{ Form::bsDate('from', optional($dateFrom)->toDateString(), [], '') }}</div>
        <div class="col-auto">{{ Form::bsDate('to', optional($dateTo)->toDateString(), [], '') }}</div>
        <div class="col-auto"><button type="submit" class="btn btn-primary">@icon(sync)</button></div>
    </div>
{{ Form::close() }}