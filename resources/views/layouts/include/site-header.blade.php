<nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between row m-0 px-0">

    <div class="col-auto d-block d-md-none">
        @if( isset($buttons['back']) && $buttons['back']['authorized'] )
            {{-- Back button --}}
            <a href="{{ $buttons['back']['url'] }}" class="btn btn-link text-light">
                @icon(arrow-left)
            </a>
        @else
            {{-- Sidebar navigation toggle --}}
            <a href="javascript:;" class="toggle-nav btn btn-link text-light toggle-button">
                @icon(navicon)
            </a>
        @endif
    </div>

    <a href="javascript:;" class="toggle-nav btn btn-link text-light toggle-button d-none d-md-inline-block ml-3">
        @icon(navicon)
    </a>

    {{-- Brand --}}
    <div class="col-auto">

        {{-- Logo, Name --}}
        <a class="navbar-brand d-none d-md-inline-block" href="{{ route('home') }}">
            <img src="{{URL::asset('/img/logo.png')}}" /> {{ Config::get('app.name') }}
        </a>
        {{-- Title --}}
        @if(View::hasSection('title'))
            <span class="text-light ml-md-4">@yield('title')</span>
        @endif

    </div>

    {{-- Right side --}}
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
