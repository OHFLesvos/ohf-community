{{ Form::open(['route' => 'bank.withdrawalSearch', 'method' => 'get']) }}
    <div class="form-row">
        <div class="col">
            <div class="input-group">
                {{ Form::search('filter', isset($filter) ? $filter : null, [ 'id' => 'filter', 'class' => 'form-control', !isset($results) ? 'autofocus' : null, 'placeholder' => 'Search for name, case number, medical number, registration number, section card number...' ]) }}
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">@icon(search)</button> 
                    @if(isset($filter))
                        <a class="btn btn-secondary" href="{{ route('bank.withdrawal') }}">@icon(eraser)</a> 
                    @endif
                </div>
            </div>
        </div>
    </div>
{{ Form::close() }}
<br>