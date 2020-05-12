<nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between row m-0 px-0">

    @auth
        <div class="col-auto d-block d-md-none pr-1 pr-sm-3">
            @if(isset($buttons['back']))
                {{-- Back button --}}
                <a href="{{ $buttons['back']['url'] }}" class="btn btn-link text-light">
                    @icon(arrow-left)
                </a>
            @else
                {{-- Sidebar navigation toggle --}}
                <a href="javascript:;" class="toggle-nav btn btn-link text-light toggle-button">
                    @icon(bars)
                </a>
            @endif
        </div>

        <a href="javascript:;" class="toggle-nav btn btn-link text-light toggle-button d-none d-md-inline-block ml-3">
            @icon(bars)
        </a>
    @endauth

    {{-- Brand --}}
    <div class="col-auto @auth px-0 px-sm-3 @endauth">

        {{-- Logo, Name --}}
        <a class="navbar-brand d-none d-md-inline-block" href="{{ route('home') }}">
            @isset($signet_url)<img src="{{ $signet_url }}" />@endisset {{ config('app.name') }}
        </a>
        {{-- Title --}}
        @if(View::hasSection('title'))
            <span class="text-light ml-md-4">@yield('title')</span>
        @endif

    </div>

    {{-- Right side --}}
    <div class="col text-right">

        {{-- Buttons --}}
        @if (isset($buttons) && sizeof($buttons) > 0)
            @foreach($buttons as $key => $button)
                @if($key == 'delete')
                    <form method="POST" action="{{ $button['url'] }}" class="d-inline">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        {{ Form::button('<i class="fa fa-' . $button['icon'] .'"></i> ' . $button['caption'], [ 'type' => 'submit', 'class' => 'btn btn-danger d-none d-md-inline-block delete-confirmation', 'data-confirmation' => $button['confirmation'] ]) }}
                        {{ Form::button('<i class="fa fa-' . $button['icon'] .'"></i>', [ 'type' => 'submit', 'class' => 'btn btn-link text-light d-md-none delete-confirmation', 'data-confirmation' => $button['confirmation'] ]) }}
                    </form>
                @elseif($key == 'action')
                    <a href="{{ $button['url'] }}" class="btn btn-primary d-none d-md-inline-block">
                        @icon({{ $button['icon'] }}) {{ $button['caption'] }}
                    </a>
                @elseif($key == 'back')
                    <a href="{{ $button['url'] }}" class="btn btn-secondary d-none d-md-inline-block">
                        @icon({{ $button['icon'] }}) {{ $button['caption'] }}
                    </a>
                @else
                    @php
                        if (isset($button['attributes'])) {
                            $attributes = collect($button['attributes'])
                                ->map(fn ($v, $k) => $k . '="' . $v . '"')
                                ->implode(' ');
                        } else {
                            $attributes = '';
                        }
                    @endphp
                    <a href="{{ $button['url'] }}" class="btn btn-secondary d-none d-md-inline-block" @if($key == 'help') target="_blank"@endif {!! $attributes !!}>
                        @icon({{ $button['icon'] }}) {{ $button['caption'] }}
                    </a>
                    <a href="{{ $button['url'] }}" class="btn text-light d-md-none" title="{{ $button['caption'] }}" @if($key == 'help') target="_blank"@endif  {!! $attributes !!}>
                        @icon({{ $button['icon'] }})
                    </a>
                @endif
            @endforeach
        @endif

        {{-- Context menu --}}
        @if (isset($menu) && sizeof($menu) > 0)
            @component('components.context-nav')
                @foreach($menu as $item)
                    <li>
                        <a href="{{ $item['url'] }}" class="btn btn-light btn-block">
                            @icon({{ $item['icon'] }} mr-1) {{ $item['caption'] }}
                        </a>
                    </li>
                @endforeach
            @endcomponent
        @endif

        @auth
            <div class="position-relative d-none d-md-inline-block">
                <button class="context-nav-toggle btn btn-link text-light px-3"><img src="{{ Auth::user()->avatarUrl('site_header') }}" alt="Gravatar" class="bg-white rounded-circle" style="width: 30px; height: 30px;"></button>
                <ul class="context-nav userprofile-nav">
                    <li>
                        <a href="{{ route('userprofile') }}" class="btn btn-dark btn-block">
                            @icon(user mr-1) @lang('userprofile.profile')
                        </a>
                    </li>
                    <li>
                        <a href="javascript:postRequest('{{ route('logout') }}', {});" class="btn btn-dark btn-block">
                            @icon(sign-out-alt mr-1) @lang('app.logout')
                        </a>
                    </li>
                </ul>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-secondary d-none d-md-inline-block">@icon(sign-in-alt) @lang('app.login')</a>
            <a href="{{ route('login') }}" class="btn text-light d-md-none">@icon(sign-in-alt)</a>
        @endauth

    </div>

</nav>
