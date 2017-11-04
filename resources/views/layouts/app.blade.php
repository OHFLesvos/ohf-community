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

        {{-- Site header --}}
        <header class="site-header">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between row m-0 px-0">
                <div class="col-auto d-block d-md-none">
                    @if( isset($buttons['back']) && $buttons['back']['authorized'] )
                        <a href="{{ $buttons['back']['url'] }}" class="btn btn-link text-light">
                            @icon(arrow-left)
                        </a>
                    @else
                        <a href="javascript:;" class="btn btn-link text-light" id="sidebar-toggle">
                            @icon(navicon)
                        </a>
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

                    {{-- Buttons --}}
                    @if ( isset( $buttons ) )
                        @foreach( $buttons as $key => $button )
                            @if ( $button['authorized'] )
                                @if( $key == 'delete' )
                                    <form method="POST" action="{{ $button['url'] }}" class="d-inline">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        {{ Form::button('<i class="fa fa-' . $button['icon'] .'"></i> ' . $button['caption'] .'</span>', [ 'type' => 'submit', 'class' => 'btn btn-danger d-none d-md-inline-block delete-confirmation', 'data-confirmation' => $button['confirmation'] ]) }}
                                        {{ Form::button('<i class="fa fa-' . $button['icon'] .'"></i>', [ 'type' => 'submit', 'class' => 'btn btn-link text-light d-md-none delete-confirmation', 'data-confirmation' => $button['confirmation'] ]) }}
                                    </form>
                                @else
                                <a href="{{ $button['url'] }}" class="btn @if( $key == 'action' )btn-primary @else btn-secondary @endif d-none d-md-inline-block">
                                    @icon({{ $button['icon'] }}) {{ $button['caption'] }}
                                </a>
                                @endif
                            @endif
                        @endforeach
                    @endif

                    {{-- Menu --}}
                    @if ( isset( $menu ) )
                        @component('components.context-nav')
                            @foreach( $menu as $item )
                                @if ( $item['authorized'] )
                                    <li>
                                        <a href="{{ $item['url'] }}" class="btn btn-light btn-block">
                                            @icon({{ $item['icon'] }}) {{ $item['caption'] }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endcomponent
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

                    {{-- Content --}}
                    @yield('content')

                    @if( isset($buttons['action']) && $buttons['action']['authorized'] )
                        @include('components.action-button', [ 'route' => $buttons['action']['url'], 'icon' => $buttons['action']['icon_floating'] ])
                    @endif

                </article>

            </main>

        </div>

        <script src="{{asset('js/app.js')}}?v={{ $app_version }}"></script>
		<script>
			@yield('script')
		</script>

    </body>
</html>
