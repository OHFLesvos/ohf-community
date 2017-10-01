<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') - {{ Config::get('app.name') }} - {{ Config::get('app.product_name') }}</title>
		
        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
		
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

		<nav class="navbar navbar-default navbar-static-top">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="{{ route('welcome') }}"><img src="{{URL::asset('/img/logo.png')}}" /> {{ Config::get('app.name') }}</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                <li class="{{ Request::is('people*') ? 'active' : '' }}"><a href="{{ route('people.index') }}"><i class="fa fa-group"></i> People</a></li>
                <li class="{{ Request::is('bank*') ? 'active' : '' }}"><a href="{{ route('bank.index') }}"><i class="fa fa-bank"></i> Bank</a></li>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>

		<div class="container-fluid">
            @yield('content')
        </div>
		
		<footer class="footer">
			<div class="container-fluid">
				<p class="text-muted">
					{{ Config::get('app.product_name') }}<span class="hidden-xs"> | <a href="{{ Config::get('app.product_url') }}" target="_blank">@app_version</a> | @environment</span>
					<span class="pull-right">&copy; Nicolas Perrenoud</span>
				</p>
			</div>
		</footer>
		
        <script src="{{asset('js/app.js')}}"></script>
		<script>
			@yield('script')
		</script>

    </body>
</html>
