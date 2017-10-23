<!doctype html>
<html lang="{{ config('app.locale') }}">
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
    <body class="p-0 m-0 bg-light">

		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8 col-lg-6 mt-md-4">

					<img src="{{URL::asset('/img/logo_login.png')}}" class="img-fluid text-center my-2 p-4" />

					<div class="card">
						<div class="card-body p-md-5">

							@yield('content')

						</div>
					</div>

                    <p class="text-center mt-2 text-muted"><small>{{ Config::get('app.product_name') }} &copy; Nicolas Perrenoud</small></p>

				</div>
			</div>
		</div>

        <script src="{{asset('js/app.js')}}?v={{ $app_version }}"></script>

    </body>
</html>
