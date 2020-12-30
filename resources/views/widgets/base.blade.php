<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between border-bottom-0">
        <span>
            @isset($icon)
                <x-icon :icon="$icon" class="fa-fw"/>
            @endisset
            {{ $title }}
        </span>
        @isset($href)
            <a href="{{ $href }}"><x-icon icon="chevron-circle-right"/></a>
        @endisset
    </div>
    @yield('widget-content')
</div>
