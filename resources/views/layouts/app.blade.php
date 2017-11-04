<!doctype html>
<html lang="{{ config('app.locale') }}" class="h-100">
    @include('layouts.include.head')
    <body class="h-100 d-flex flex-column">

        <div id="overlay" class="position-absolute h-100 w-100"></div>
        <div id="overlay_dark" class="position-absolute h-100 w-100"></div>

        {{-- Sidenav --}}
        <div id="mySidenav" class="sidenav bg-light flex-column d-flex">
            <div class="px-3 pt-3">
                <span class="navbar-brand">
                    <img src="{{URL::asset('/img/logo.png')}}" /> {{ Config::get('app.name') }}
                </span>
            </div>
            @include('layouts.include.side-nav')
        </div>

        <header class="site-header">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between row m-0 px-0">
                <div class="col-auto d-block d-md-none">
                    @if(View::hasSection('backLink'))
                        <a href="@yield('backLink')" class="btn btn-link text-light">@icon(arrow-left)</a>
                    @else
                        <a href="javascript:;" class="btn btn-link text-light" id="sidebar-toggle">@icon(navicon)</a>
                    @endif
                </div>
                <div class="col-auto">
                    <a class="navbar-brand d-none d-md-inline-block" href="{{ route('home') }}">
                        <img src="{{URL::asset('/img/logo.png')}}" /> {{ Config::get('app.name') }}
                    </a>
                    @if(View::hasSection('title'))
                        <span class="text-light ml-md-4">@yield('title')</span>
                    @endif
                </div>
                <div class="col text-right">
                    @if(View::hasSection('buttons'))
                        @yield('buttons')
                    @endif
                </div>
            </nav>
        </header>

        <div class="main-wrapper h-100 d-flex">

            <main class="main d-flex">

                {{-- Sidebar --}}
                <aside class="sidebar flex-column bg-light text-dark d-none d-md-flex" id="sidebar">
                    @include('layouts.include.side-nav')
                </aside>

                <article class="main-content p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            @icon(check) {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show">
                            @icon(info-circle) {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show">
                            @icon(warning) Validation failed, you have entered invalid values!
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

                    @yield('content')

                </article>

            </main>

        </div>

        <script src="{{asset('js/app.js')}}?v={{ $app_version }}"></script>
		<script>
			@yield('script')
		</script>

    </body>
</html>
