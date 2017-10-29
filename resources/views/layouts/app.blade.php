<!doctype html>
<html lang="{{ config('app.locale') }}" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@if(View::hasSection('title')) @yield('title') - @endif{{ Config::get('app.name') }} - {{ Config::get('app.product_name') }}</title>
		
        <link href="{{asset('css/app.css')}}?v={{ $app_version }}" rel="stylesheet" type="text/css">
		
		<link rel="icon" href="{{URL::asset('/img/favicon-32x32.png')}}" sizes="32x32" />
		<link rel="icon" href="{{URL::asset('/img/favicon-192x192.png')}}" sizes="192x192" />
		<link rel="apple-touch-icon-precomposed" href="{{URL::asset('/img/favicon-180x180.png')}}" />
		
		<!-- Scripts -->
		<script>
			window.Laravel = <?php echo json_encode([
				'csrfToken' => csrf_token(),
			]); ?>
		</script>

    </head>
    <body class="h-100 d-flex flex-column">

        <header class="site-header">

            <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between">
                <span>
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img src="{{URL::asset('/img/logo.png')}}" /> {{ Config::get('app.name') }}
                    </a>
                    @if(View::hasSection('title'))
                        <span class="text-light ml-4">@yield('title')</span>
                    @endif
                </span>
                @if(View::hasSection('buttons'))
                    <span>
                        @yield('buttons')
                    </span>
                @endif
            </nav>

        </header>

        <div class="main-wrapper h-100 d-flex">

            <main class="main d-flex">

                <aside class="sidebar d-flex flex-column bg-light text-dark">

                    {{-- Navigation --}}
                    <ul class="nav flex-column nav-pills my-3">
                        @foreach ($nav as $n)
                            @if ($n['authorized'])
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is($n['active']) ? 'active' : '' }}" href="{{ route($n['route']) }}">
                                        <i class="fa fa-{{ $n['icon'] }}" title="{{ $n['caption'] }}"></i>
                                        <span class=" d-none d-sm-inline">  {{ $n['caption'] }}</span>
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

                        <hr class="d-none d-sm-block">
                        <div class="text-center d-none d-sm-block">
                            <a href="{{ route('userprofile') }}"><h1 class="display-4"><i class="fa fa-user"></i></h1></a>
                            {{ Auth::user()->name }}
                        </div>
                        <a href="{{ route('userprofile') }}" class="d-block d-sm-none btn btn-block {{ Request::is('userprofile') ? 'btn-primary' : '' }}"><i class="fa fa-user"></i></a>

                        {{-- Logout --}}
                        <div class="px-sm-3 mt-sm-3">
                            <form class="form-inline" action="{{ route('logout') }}" method="POST">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-secondary btn-block"><i class="fa fa-sign-out"></i><span class=" d-none d-sm-inline">  Logout</span></button>
                            </form>
                        </div>

                        <hr class="d-none d-sm-block">
                        <p class="copyright text-muted px-3 d-none d-sm-block">
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
                            <i class="fa fa-check"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show">
                            <i class="fa fa-info-circle"></i> {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fa fa-warning"></i> Validation failed, you have entered invalid values!
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
