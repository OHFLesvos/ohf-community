@extends('layouts.centered')

@section('title', __('Login'))

@section('centered-content')

    @isset($logo_url)
        <div>
            <img src="{{ $logo_url }}" class="img-fluid text-center my-sm-0 p-4" alt="Logo" />
        </div>
    @endisset

    <div class="card shadow mb-4">
        <div class="card-body">

            <h1 class="display-4 text-center mb-4">@yield('title')</h1>

            @if (session('error'))
                <x-alert type="danger" dismissible>
                    {{ session('error') }}
                </x-alert>
            @endif

            @yield('content')

        </div>
    </div>

    <p class="text-center">
        <small>
            @foreach (language()->allowed() as $code => $name)
                <a href="{{ language()->back($code) }}">
                    @if (App::getLocale() == $code)
                        <strong>{{ $name }}</strong>@else{{ $name }}
                    @endif
                </a> &nbsp;
            @endforeach
        </small>
    </p>

    <footer class="text-center border-top pt-3 pb-2">
        <small>
            {!! __('By using this service, you accept our <a href=":url" target="_blank">privacy policy</a>.', [
                'url' => route('privacyPolicy'),
            ]) !!}
        </small>
    </footer>

@endsection
