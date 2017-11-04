<!doctype html>
<html lang="{{ config('app.locale') }}" class="h-100">
    @include('layouts.include.head')
    <body class="h-100 d-flex flex-column">

        <div id="overlay" class="position-absolute h-100 w-100"></div>

        <header class="site-header">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between row m-0 px-0">
                <div class="col-auto">
                    <button class="btn btn-link text-light pr-0" id="sidebar-toggle">@icon(navicon)</button>
                </div>
                <div class="col-auto">
                    <a class="navbar-brand d-none d-md-inline-block" href="{{ route('home') }}">
                        <img src="{{URL::asset('/img/logo.png')}}" /> {{ Config::get('app.name') }}
                    </a>
                    @if(View::hasSection('title'))
                        <span class="text-light ml-xs-4">@yield('title')</span>
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

                <aside class="sidebar flex-column bg-light text-dark d-none" id="sidebar">

                    <div class="mt-4 d-block d-md-none">
                        <span class="p-4">
                            <img src="{{URL::asset('/img/logo.png')}}" class=" mr-1"/> <small>{{ Config::get('app.name') }}</small>
                        </span>
                    </div>

                    {{-- Navigation --}}
                    <ul class="nav flex-column nav-pills my-3 mt-0">
                        @foreach ($nav as $n)
                            @if ($n['authorized'])
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is($n['active']) ? 'active' : '' }}" href="{{ route($n['route']) }}">
                                        <i class="fa fa-{{ $n['icon'] }}" title="{{ $n['caption'] }}"></i> {{ $n['caption'] }}
                                        @if ($n['route'] == 'tasks.index' and $num_open_tasks > 0)
                                            <span class="badge badge-secondary">{{ $num_open_tasks }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    {{-- Footer --}}
                    <footer class="footer">

                        <hr>
                        <div class="text-center">
                            <a href="{{ route('userprofile') }}"><h1 class="display-4">@icon(user)</h1></a>
                            {{ Auth::user()->name }}
                        </div>

                        {{-- Logout --}}
                        <div class="px-3 mt-3">
                            <form class="form-inline" action="{{ route('logout') }}" method="POST">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-block btn-secondary">@icon(sign-out) Logout</button>
                            </form>
                        </div>

                        <hr>
                        <p class="copyright text-muted px-3">
                            <a href="{{ Config::get('app.product_url') }}" target="_blank" class="text-dark">{{ Config::get('app.product_name') }}</a><br>
                            Version: {{ $app_version }}<br>
                            &copy; Nicolas Perrenoud<br>
                            Page rendered in {{ round((microtime(true) - LARAVEL_START)*1000) }} ms
                        </p>
                    </footer>

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
