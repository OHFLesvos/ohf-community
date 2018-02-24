<div class="card mb-4">
    <div class="card-header">
        @lang('app.tools')
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @foreach($tools as $o)
                <a href="{{ route($o['route']) }}" class="list-group-item list-group-item-action">
                    @icon({{ $o['icon'] }}) {{ $o['name'] }}
                </a>
            @endforeach                    
        </div>
    </div>
</div>
