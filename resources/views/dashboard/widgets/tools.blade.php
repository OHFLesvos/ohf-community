@extends('dashboard.widgets.base')

@section('widget-title', __('app.tools'))

@section('widget-content')
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @foreach($tools as $o)
                <a href="{{ route($o['route']) }}" class="list-group-item list-group-item-action">
                    @icon({{ $o['icon'] }}) {{ $o['name'] }}
                </a>
            @endforeach                    
        </div>
    </div>
@endsection