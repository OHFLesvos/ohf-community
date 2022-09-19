@if(!empty($oauth_services))
@foreach($oauth_services as $service)
    <div class="text-center mb-2">
        <a href="{{ route('login.provider', $service['name']) }}" class="btn btn-secondary btn-block">
            <x-icon :icon="$service['icon']" style="fab" class="mr-1"/>
            {{ $service['label'] }}
        </a>
    </div>
@endforeach
<x-separator-text>{{ __('or') }}</x-separator-text>
@endif
