<nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between row m-0 px-0">

    @auth
        <div class="col-auto d-block d-md-none pr-1 pr-sm-3">
            @if(isset($buttons['back']))
                {{-- Back button --}}
                <a href="{{ $buttons['back']['url'] }}" class="btn btn-link text-light">
                    <x-icon icon="arrow-left"/>
                </a>
            @else
                {{-- Sidebar navigation toggle --}}
                <a href="javascript:;" class="toggle-nav btn btn-link text-light toggle-button">
                    <x-icon icon="bars"/>
                </a>
            @endif
        </div>

        <a href="javascript:;" class="toggle-nav btn btn-link text-light toggle-button d-none d-md-inline-block ml-3">
            <x-icon icon="bars"/>
        </a>
    @endauth

    {{-- Brand --}}
    <div class="col-auto @auth px-0 px-sm-3 @endauth">

        {{-- Logo, Name --}}
        <a class="navbar-brand d-none d-md-inline-block" href="{{ route('home') }}">
            @isset($signet_url)<img src="{{ $signet_url }}" alt="Brand" />@endisset {{ config('app.name') }}
        </a>
        {{-- Title --}}
        @if(View::hasSection('title'))
            <span class="text-light ml-md-4">@yield('title')</span>
        @endif

    </div>

    {{-- Right side --}}
    <div class="col text-right">

        @auth
            <div class="position-relative @if((isset($menu) && sizeof($menu) > 0) || (isset($buttons) && sizeof($buttons) > 0)) d-none d-md-inline-block @endif">
                <button class="context-nav-toggle btn btn-link text-light px-3 py-0">
                    <x-user-avatar :user="Auth::user()" size="32"/>
                </button>
                <ul class="context-nav userprofile-nav">
                    <li>
                        <a href="{{ route('userprofile') }}" class="btn btn-dark btn-block rounded-0">
                            <x-icon icon="user" class="mr-1"/>
                            {{ __('Profile') }}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:postRequest('{{ route('logout') }}', {});" class="btn btn-dark btn-block rounded-0">
                            <x-icon icon="right-from-bracket" class="mr-1"/>
                            {{ __('Logout') }}
                        </a>
                    </li>
                </ul>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-secondary d-none d-md-inline-block"><x-icon icon="right-to-bracket"/> {{ __('Login') }}</a>
            <a href="{{ route('login') }}" class="btn text-light d-md-none"><x-icon icon="right-to-bracket"/></a>
        @endauth

    </div>

</nav>
