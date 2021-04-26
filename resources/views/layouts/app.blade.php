<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
    @include('layouts.include.head')
    <body class="h-100 d-flex flex-column">

        <div class="site-wrapper h-100">

            <div class="site-canvas h-100">

                {{-- Side navigation --}}
                <nav class="site-navigation bg-light">
                    @include('layouts.include.side-nav')
                </nav>

                {{-- Main --}}
                <main class="d-flex flex-column h-100">

                    {{-- Site header --}}
                    <header class="site-header">
                        @include('layouts.include.site-header')
                    </header>

                    {{-- Sub Navigation --}}
                    @isset($subnav)
                        @php
                            $items = collect($subnav)->where('authorized', true);
                        @endphp
                        @if($items->isNotEmpty())
                            <div class="nav-scroller bg-white shadow-sm">
                                <nav class="nav nav-underline">
                                    @foreach($items as $item)
                                        <a class="nav-link @if(isset($item['active']) && Request::is($item['active'])) active @endisset" href="{{ $item['url'] }}">@isset($item['icon'])<x-icon :icon="$item['icon']"/> @endisset{{ $item['caption'] }}</a>
                                    @endforeach
                                </nav>
                            </div>
                        @endif
                    @endisset

                    {{-- Content --}}
                    <article class="site-content bg-light">

                        <div class="@if(! isset($wide_layout) || $wide_layout) container-fluid @else container @endif {{ $content_padding ?? 'pt-3' }}">

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
                                    @lang('Validation failed, you have entered invalid values!')
                                    <ul class="mb-0 pb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </x-alert>
                            @endif

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

                        </div>

                    </article>

                    <div id="overlay" class="position-absolute h-100 w-100"></div>

                    <div id="overlay_dark" class="position-absolute h-100 w-100"></div>

                </main>

            </div>

        </div>

        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{ mix('js/vendor.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>

        {{-- Stack for additional scripts --}}
        @stack('footer')
    </body>
</html>
