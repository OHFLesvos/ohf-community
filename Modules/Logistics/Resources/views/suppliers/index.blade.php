{{-- @extends('logistics::layouts.suppliers-products') --}}
@extends('layouts.app')

@section('title', __('logistics::suppliers.suppliers'))

{{-- @section('wrapped-content') --}}
@section('content')

    @php
        $mapped_suppliers = $suppliers->filter(function($s){ 
                return $s->poi->latitude != null && $s->poi->longitude != null; 
            });
    @endphp

    <div class="form-row">
        <div class="col">
            {!! Form::open(['route' => ['logistics.suppliers.index'], 'method' => 'get']) !!}
                <div class="input-group mb-3">
                    {{ Form::search('filter', isset($filter) ? $filter : null, [ 'class' => 'form-control form-control-sm', 'placeholder' => __('app.filter') . '...' ]) }}
                    <div class="input-group-append">
                        <button class="btn btn-primary btn-sm" type="submit">@icon(search)</button> 
                        @if(isset($filter))
                            <a class="btn btn-secondary btn-sm" href="{{ route('logistics.suppliers.index', ['reset_filter']) }}">@icon(eraser)</a> 
                        @endif
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
        <div class="col-auto">
            <div class="btn-group" role="group">
                <a href="{{ route('logistics.suppliers.index', ['display' => 'list']) }}" class="btn btn-sm @if($display=='list') btn-dark @else btn-secondary @endif">@icon(list)</a>
                @if(!$mapped_suppliers->isEmpty())
                    <a href="{{ route('logistics.suppliers.index', ['display' => 'map']) }}" class="btn btn-sm @if($display=='map') btn-dark @else btn-secondary @endif">@icon(map)</a>
                @endif
            </div>
        </div>
    </div>

    @if( ! $suppliers->isEmpty() )
        @if( $display == 'list' )

            <ul class="list-group mb-4">

                {{-- <li class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <small>{{ $suppliers->count() }} records found</small>
                        <small>{{ $suppliers->total() }} records in total</small>
                    </div>
                </a> --}}

                @foreach ($suppliers as $supplier)
                    @php
                        $links = [];
                        if (isset($supplier->phone)) {
                            $links[] = [
                                'icon' => 'phone',
                                'url' => tel_url($supplier->phone),
                                'text' => $supplier->phone,
                            ];
                        }
                        if (isset($supplier->email)) {
                            $links[] = [
                                'icon' => 'envelope',
                                'url' => email_url($supplier->email),
                                'text' => $supplier->email,
                            ];
                        }
                        if (isset($supplier->website)) {
                           $links[] = [
                                'icon' => 'globe',
                                'url' => $supplier->website,
                                'text' => simplified_url($supplier->website),
                                'attributes' => [ 'target' => '_blank' ],
                            ];
                        }
                    @endphp

                    <li class="list-group-item">

                        {{-- Title --}}
                        <h5 class="mb-1">
                            @can('view', $supplier)<a href="{{ route('logistics.suppliers.show', $supplier) }}">@endcan
                                {{ $supplier->poi->name }}
                            @can('view', $supplier)</a>@endcan
                        </h5>

                        {{-- Category --}}
                        <h6 class="mb-2 text-muted">
                            {{ $supplier->category }}
                        </h6>
                      
                        {{-- Address --}}
                        @isset($supplier->poi->address)
                            {{ $supplier->poi->address }}
                            @if($supplier->poi->maps_location != null)
                                <a href="{{ gmaps_url($supplier->poi->maps_location) }}" target="_blank" class="d-none d-sm-inline">@icon(map)</a>
                            @endif
                        @endisset

                        {{-- Links (desktop) --}}
                        @if(count($links) > 0)
                            <div class="d-none d-sm-block mt-2">
                                @foreach($links as $link)
                                    @unless($loop->first) | @endunless
                                    @icon({{ $link['icon'] }}) <a href="{{ $link['url'] }}" {!! isset($link['attributes']) ? print_html_attributes($link['attributes']) : '' !!}>{{ $link['text'] }}</a>
                                @endforeach
                            </div>
                        @endif
                        {{-- Links (mobile) --}}
                        @if(count($links) > 0 || $supplier->poi->maps_location != null)
                            <div class="d-sm-none mt-3 mb-1 d-flex w-100 justify-content-between">
                                @if($supplier->poi->maps_location != null)
                                    <a href="{{ gmaps_url($supplier->poi->maps_location) }}" target="_blank" class="btn btn-secondary rounded-circle">@icon(map)</a>
                                @endif
                                @foreach($links as $link)
                                    <a href="{{ $link['url'] }}" {!! isset($link['attributes']) ? print_html_attributes($link['attributes']) : '' !!} class="btn btn-secondary rounded-circle">@icon({{ $link['icon'] }})</a>
                                @endforeach
                            </div>
                        @endif

                    </li>
                @endforeach            
            </ul>

            {{ $suppliers->links() }}

        @elseif( $display == 'map' )

            <div id="map" style="width: 100%; height: 500px;" class="mb-3"></div>
            <script src="//maps.googleapis.com/maps/api/js?libraries=places,drawing&amp;key={{ env('GOOGLE_MAPS_API_KEY') }}" type="text/javascript"></script>
            <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
            <script type="text/javascript">
                function initMap() {
                    var bounds  = new google.maps.LatLngBounds();
                    var map = new google.maps.Map(document.getElementById('map'), {
//                        zoom: 3,
//                        center: {lat: -28.024, lng: 140.887},
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        mapTypeControlOptions : {
                            mapTypeIds : [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE],
                        },
                        gestureHandling: 'cooperative',
                    });

                    // Create an array of alphabetical characters used to label the markers.
                    //var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

                    var infowindow = new google.maps.InfoWindow();

                    // Add some markers to the map.
                    // Note: The code uses the JavaScript Array.prototype.map() method to
                    // create an array of markers based on a given "locations" array.
                    // The map() method here has nothing to do with the Google Maps API.
                    var markers = locations.map(function(location, i) {
                        marker = new google.maps.Marker({
                            position: location,
                            //label: labels[i % labels.length],
                            //icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
                            //title: 'foo',
                        });
                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            return function() {
                                infowindow.setContent('<strong>' + location.name + '</strong><br><small>' + location.category + '</small><br><br>' + location.address);
                                infowindow.open(map, marker);
                            }
                        })(marker, i));
                        bounds.extend(new google.maps.LatLng(marker.position.lat(), marker.position.lng()));
                        return marker;
                    });

                    // Add a marker clusterer to manage the markers.
                    var markerCluster = new MarkerClusterer(map, markers,
                        {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

                    map.fitBounds(bounds);
                    map.panToBounds(bounds);
                }
                var locations = [
                    @foreach ($mapped_suppliers as $supplier)
                        {
                            lat: {{ $supplier->poi->latitude }}, 
                            lng: {{ $supplier->poi->longitude }}, 
                            name: '{{ $supplier->poi->name }}', 
                            address: '{{ $supplier->poi->address }}', 
                            category: '{{ $supplier->category }}',
                        },
                    @endforeach
                ]
                google.maps.event.addDomListener(window, 'load', initMap);
            </script>
        @endif

    @else
        @component('components.alert.info')
            @lang('logistics::suppliers.no_suppliers_found')
        @endcomponent
	@endif
@endsection