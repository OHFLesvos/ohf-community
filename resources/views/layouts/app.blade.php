<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
    @include('layouts.include.head')
    <body class="app-grid h-100">

        <div class="app-brand py-3 px-4 d-flex align-items-center justify-content-between">
            <div class="text-md-center w-100">
                @isset($signet_url)
                    <a href="{{ route('home') }}"><img src="{{ $signet_url }}" alt="Brand" /></a>
                @endisset
            </div>
            <button class="nav-toggle d-md-none btn btn-link text-white"><x-icon icon="bars"/></button>
        </div>

        <header class="app-header shadow-sm bg-white py-3 px-4 d-flex align-items-center justify-content-between">
            <span class="brand">{{ config('app.name') }}</span>
            <span class="user-menu">
                @auth
                    <a href="{{ route('userprofile') }}"><x-user-avatar :user="Auth::user()" size="32"/></a>
                    {{-- TODO Logout --}}
                @endauth
            </span>
        </header>

        <div class="app-sidebar py-2 py-md-3 d-none d-md-block">
            <ul class="p-0 m-0">
                @foreach ($nav as $n)
                    <li class="py-2 px-4">
                        <a class="{{ Request::is($n->getActive()) ? 'active' : '' }}" href="{{ $n->getRoute() }}">
                            <span class="d-inline-block" style="width: 26px"><x-icon :icon="$n->getIcon()" /></span>
                            {{ $n->getCaption() }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <main class="app-content px-3 px-md-4 px-lg-5 pt-3 pt-md-4 pb-0 pb-mb-2">

            {{-- Success message --}}
            @if (session('success'))
                <div class="snack-message">
                    <x-icon icon="check-square" class="pr-1"/>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Info message --}}
            @if (session('info'))
                <div class="snack-message">
                    {{ session('info') }}
                </div>
            @endif

            {{-- Error message --}}
            @if (session('error'))
                <x-alert type="danger" dismissible>
                    {{ session('error') }}
                </x-alert>
            @endif

            {{-- Validation error --}}
            @if (count($errors) > 0)
                <x-alert type="warning" dismissible>
                    @lang('app.validation_failed')
                    <ul class="mb-0 pb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <div class="d-flex justify-content-between">
                {{-- Title --}}
                @if(View::hasSection('title'))
                    <h1 class="mb-4 display-4">@yield('title')</h1>
                @endif

                {{-- Buttons --}}
                @if(isset($buttons) && sizeof($buttons) > 0)
                    <div>
                        @foreach($buttons as $key => $button)
                            @if($key == 'delete')
                                <form method="POST" action="{{ $button['url'] }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-form.bs-delete-button
                                        :label="$button['caption']"
                                        :icon="$button['icon']"
                                        :confirmation="$button['confirmation']"
                                        class="d-none d-md-inline-block"
                                    />
                                    <button
                                        type="submit"
                                        class="btn btn-link text-light d-md-none delete-confirmation"
                                        data-confirmation="{{ $button['confirmation']  }}"
                                    >
                                        <x-icon :icon="$button['icon']"/>
                                    </button>
                                </form>
                            @elseif($key == 'action')
                                <a href="{{ $button['url'] }}" class="btn btn-primary d-none d-md-inline-block">
                                    <x-icon :icon="$button['icon']"/>
                                    {{ $button['caption'] }}
                                </a>
                            {{-- @elseif($key == 'back')
                                <a href="{{ $button['url'] }}" class="btn btn-secondary d-none d-md-inline-block">
                                    <x-icon :icon="$button['icon']"/>
                                    {{ $button['caption'] }}
                                </a> --}}
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
                                <a href="{{ $button['url'] }}" class="btn btn-secondary" @if($key == 'help') target="_blank"@endif {!! $attributes !!}>
                                    <x-icon :icon="$button['icon']"/>
                                    <span class="d-none d-md-inline">{{ $button['caption'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>


            {{-- Content --}}
            @yield('content')

            {{-- Floating action button --}}
            @if(isset($buttons['action']) && $buttons['action']['authorized'] )
                <a
                    href="{{ $buttons['action']['url'] }}"
                    class="btn btn-primary btn-lg d-md-none floating-action-button"
                >
                    <x-icon :icon="$buttons['action']['icon_floating']"/>
                </a>
            @endif

        </main>

        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{ mix('js/vendor.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>

        {{-- Stack for additional scripts --}}
        @stack('footer')

        <script>
            $(function(){
                $('.nav-toggle').on('click', function(){
                    $('.app-sidebar').toggleClass('d-none');
                });
            })
        </script>
    </body>
</html>
