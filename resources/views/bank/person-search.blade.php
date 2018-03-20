{{ Form::open(['route' => 'bank.withdrawalSearch', 'method' => 'get']) }}
    <div class="form-row">
        <div class="col">
            <div class="input-group">
                {{ Form::search('filter', isset($filter) ? $filter : null, [ 'id' => 'filter', 'class' => 'form-control' .(isset($results) && count($results) == 0 ? ' focus-tail' : ''), !isset($results) ? 'autofocus' : null, 'placeholder' => __('people.bank_search_text') ]) }}
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">@icon(search)</button> 
                    @if(isset($filter))
                        <a class="btn btn-secondary" href="{{ route('bank.withdrawal') }}">@icon(eraser)</a> 
                    @endif
                </div>
            </div>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="button" id="scan-id-button">@icon(qrcode)<span class="d-none d-sm-inline"> Scan</span></button> 
        </div>
    </div>
{{ Form::close() }}
<br>