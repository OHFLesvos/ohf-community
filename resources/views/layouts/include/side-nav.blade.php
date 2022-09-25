@auth
<div class="h-100 d-flex flex-column">
    <header class="side-nav-header">

        {{-- Logo --}}
        <div class="px-3 pt-3">
            <span class="navbar-brand text-wrap">
                @isset($signet_url)<img src="{{ $signet_url }}" alt="Brand"/>@endisset
                {{ config('app.name') }}
            </span>
        </div>

        {{-- Navigation --}}
        <ul class="nav flex-column nav-pills my-3 mt-0">
            @foreach ($nav as $n)
                <li class="nav-item">
                    <a class="nav-link rounded-0 {{ Request::is($n->getActive()) ? 'active' : '' }}" href="{{ $n->getRoute() }}">
                        <x-icon :icon="$n->getIcon()" fixed-width />
                        {{ $n->getCaption() }}
                    </a>
                </li>
            @endforeach
        </ul>

    </header>

    {{-- Footer --}}
    <footer class="side-nav-footer">

        <hr>
        <div class="text-center">
            <a href="{{ route('userprofile') }}" class="d-block mb-1">
                <x-user-avatar :user="Auth::user()" size="80"/>
            </a>
            {{ Auth::user()->name }}
        </div>

        {{-- Logout --}}
        <div class="px-3 mt-3">
            <form class="form-inline" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-block btn-secondary"><x-icon icon="right-from-bracket"/> {{ __('Logout') }}</button>
            </form>
        </div>

        <hr>
        <p class="copyright text-muted px-3">
            {{ config('app.name') }}<br>
            Revision: <a href="{{ route('changelog') }}" target="_blank">{{ $app_version }}</a><br>
            Environment: {{ app()->environment() }}<br><br>
            @include('layouts.include.copyright')
        </p>
    </footer>
</div>
@endauth
