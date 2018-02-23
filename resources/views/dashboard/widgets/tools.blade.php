@if (sizeof($other) > 0)
    <div class="card mb-4">
        <div class="card-header">
            Other tools
        </div>
        <div class="card-body">
            <div class="list-group">
                @foreach($other as $o)
                    <a href="{{ route($o['route']) }}" class="list-group-item list-group-item-action">@icon({{ $o['icon'] }}) {{ $o['name'] }}</a>
                @endforeach                    
            </div>
        </div>
    </div>
@endif