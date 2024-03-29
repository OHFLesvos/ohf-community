@if(!empty($oauth_services))
@foreach($oauth_services as $service)
    <div class="text-center mb-2">
        <a href="{{ route('login.provider', $service['name']) }}" class="btn btn-secondary btn-block">
            <x-icon :icon="$service['icon']" style="brands" class="mr-1"/>
            {{ $service['label'] }}
        </a>
    </div>
@endforeach
<x-strike-text>{{ __('or') }}</x-strike-text>
@endif
