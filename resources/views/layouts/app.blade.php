<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') - {{ Config::get('app.name') }} - {{ Config::get('app.product_name') }}</title>
		
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
    <body>

		<nav class="navbar navbar-expand-lg navbar-dark bg-primary header-nav">
			<a class="navbar-brand" href="{{ route('welcome') }}"><img src="{{URL::asset('/img/logo.png')}}" /> {{ Config::get('app.name') }}</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item {{ Request::is('people*') ? 'active' : '' }}">
					<a class="nav-link" href="{{ route('people.index') }}"><i class="fa fa-group"></i> People</a>
				</li>
				<li class="nav-item {{ Request::is('bank*') ? 'active' : '' }}">
					<a class="nav-link" href="{{ route('bank.index') }}"><i class="fa fa-bank"></i> Bank</a>
				</li>
				<li class="nav-item {{ Request::is('tasks*') ? 'active' : '' }}">
					<a class="nav-link" href="{{ route('tasks.index') }}"><i class="fa fa-tasks"></i> Tasks @if ($num_open_tasks > 0)<span class="badge badge-secondary">{{ $num_open_tasks }}</span>@endif</a>
				</li>
			</ul>
			</div>
		</nav>

		<div class="container-fluid">
            @yield('content')
        </div>
		
		<footer class="footer bg-light text-dark">
			<div class="container-fluid">
				<p>
					{{ Config::get('app.product_name') }}<span class="d-none d-sm-inline"> | <a href="{{ Config::get('app.product_url') }}" target="_blank" class="text-dark">{{ $app_version }}</a> | @environment</span>
					<span class="pull-right">&copy; Nicolas Perrenoud</span>
				</p>
			</div>
		</footer>
		
        <script src="{{asset('js/app.js')}}?v={{ $app_version }}"></script>
		<script>
			@yield('script')
		</script>

    </body>
</html>
