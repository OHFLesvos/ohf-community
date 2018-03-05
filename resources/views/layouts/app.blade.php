<!doctype html>
<html lang="{{ config('app.locale') }}" class="h-100">
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

                    {{-- Content --}}
                    <article class="site-content container-fluid pt-3">

                        {{-- Success message --}}
                        @if (session('success'))
                            <div class="snack-message" data-class="success" data-action="@lang('app.dismiss')">{{ session('success') }}</div>
                        @endif

                        {{-- Info message --}}
                        @if (session('info'))
                            <div class="snack-message" data-class="info" data-action="@lang('app.dismiss')">{{ session('info') }}</div>
                        @endif

                        {{-- Error message --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                @icon(warning) {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Validation error --}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade show">
                                @icon(warning) @lang('app.validation_failed')
                                {{--
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                --}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Content --}}
                        @yield('content')

                        {{-- Floating action button --}}
                        @if( isset($buttons['action']) && $buttons['action']['authorized'] )
                            @include('components.action-button', [ 'route' => $buttons['action']['url'], 'icon' => $buttons['action']['icon_floating'] ])
                        @endif

                    </article>

                    <div id="overlay" class="position-absolute h-100 w-100"></div>

                    <div id="overlay_dark" class="position-absolute h-100 w-100"></div>

                </main>

            </div>

        </div>

    <!-- Modal -->
    <div class="modal" id="videoPreviewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">QR Code Scanner</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <video id="preview" style="width: 100%; height: 100%"></video>
            </div>
          </div>
        </div>
      </div>

        @yield('content-footer')

        <script src="{{ asset('js/app.js') }}?v={{ $app_version }}"></script>
        <script>
            @yield('script')
        </script>
		@yield('footer')

    </body>
</html>
