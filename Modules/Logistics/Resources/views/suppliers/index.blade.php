{{-- @extends('logistics::layouts.suppliers-products') --}}
@extends('layouts.app')

@section('title', __('logistics::suppliers.suppliers'))

{{-- @section('wrapped-content') --}}
@section('content')

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
                <a href="{{ route('logistics.suppliers.index', ['display' => 'map']) }}" class="btn btn-sm @if($display=='map') btn-dark @else btn-secondary @endif">@icon(map)</a>
            </div>
        </div>
    </div>

    @if( ! $suppliers->isEmpty() )
        @if( $display == 'list' )

            @foreach ($suppliers as $supplier)
                <div class="card mb-3">
                    <div class="card-body">
                        @can('update', $supplier)
                            <a href="{{ route('logistics.suppliers.edit', $supplier) }}" class="float-right btn-link">@icon(edit)</a>
                        @endcan                    
                        <h5 class="card-title">{{ $supplier->poi->name_tr }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $supplier->category }}</h6>
                        @isset($supplier->poi->description)
                            <p class="card-text">{{ $supplier->poi->description }}</p>
                        @endisset
                        {{-- <div class="row mb-3"> --}}
                        <div class="row">
                            <div class="col-sm">
                                <p class="card-text">
                                    @icon(map-marker) {!! gmaps_link(str_replace(", ", "<br>", $supplier->poi->address_tr), $supplier->poi->maps_location) !!}
                                    @isset($supplier->phone)
                                        <br>@icon(phone) {!! tel_link($supplier->phone) !!}
                                    @endisset
                                </p>
                            </div>
                            <div class="col-sm">
                                <p class="card-text">
                                    @isset($supplier->email)
                                        @icon(envelope) {!! email_link($supplier->email) !!}<br>
                                    @endisset
                                    @isset($supplier->website)
                                        @icon(globe) <a href="{{ $supplier->website }}" target="_blank">{{ $supplier->website }}</a><br>
                                    @endisset
                                </p>
                            </div>
                        </div>
                        {{-- <a href="#" class="card-link text-dark">@icon(shopping-basket) Products</a>
                        <a href="#" class="card-link text-dark">@icon(file-text-o) Services</a> --}}
                        {{-- <p class="card-text"><small class="text-muted"><a href="" class="text-muted">Products: Apples, Pears, Wrenches, ...</a></small></p> --}}
                    </div>
                </div>
            @endforeach
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
                    @foreach ($suppliers->filter(function($s){ return $s->poi->latitude != null && $s->poi->longitude != null; }) as $supplier)
                        {
                            lat: {{ $supplier->poi->latitude }}, 
                            lng: {{ $supplier->poi->longitude }}, 
                            name: '{{ $supplier->poi->name_tr }}', 
                            address: '{{ $supplier->poi->address_tr }}', 
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