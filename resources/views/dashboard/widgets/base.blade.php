<div class="card mb-4">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                @yield('widget-title')
            </div>
            <div class="col-auto pr-2">
                @isset($links)
                    @foreach($links as $link)
                        @if($link['authorized'])
                            <a href="{{ $link['url'] }}" class="btn btn-sm btn-outline-primary @if(!$loop->last) mr-1 @endif" title="{{ $link['title'] }}">
                                @icon({{ $link['icon'] }})
                                <span class="d-none d-xl-inline"> {{ $link['title'] }}</span>
                            </a>
                        @endif
                    @endforeach
                @endisset
            </div>
        </div>
    </div>
    @yield('widget-content')
</div>
