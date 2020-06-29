@extends('backends.layouts.master')
@section('title', __('customer.title'))
@section('content')
<div id="map-canvas-customer" style="height: 80vh; width: 100%; position: relative; overflow: hidden;"></div>
@endsection
@push('footer-script')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script type='text/javascript' src='//maps.google.com/maps/api/js?language=en&key={{ config('app.google_api_map') }}&libraries=places&region=GB'></script>
<script defer>
	function initialize() {
		var mapOptions = {
			zoom: 12,
			minZoom: 6,
			maxZoom: 17,
			zoomControl:true,
			zoomControlOptions: {
  				style:google.maps.ZoomControlStyle.DEFAULT
			},
			center: new google.maps.LatLng({{ $latitude }}, {{ $longitude }}),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			scrollwheel: false,
			panControl:false,
			mapTypeControl:false,
			scaleControl:false,
			overviewMapControl:false,
			rotateControl:false
	  	}
		var map = new google.maps.Map(document.getElementById('map-canvas-customer'), mapOptions);
        var image = new google.maps.MarkerImage("{{asset('images/pin.png')}}", null, null, null, new google.maps.Size(32,32));
        var customerMaps = @json($customerMaps);
        for(customerMap in customerMaps) {
            customerMap = customerMaps[customerMap];
            if(customerMap.latitude && customerMap.longitude)
            {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(customerMap.latitude, customerMap.longitude),
                    icon:image,
                    map: map,
                    title: customerMap.name
                });
                var infowindow = new google.maps.InfoWindow();
                google.maps.event.addListener(marker, 'click', (function (marker, customerMap) {
                    return function () {
                        infowindow.setContent(generateContent(customerMap))
                        infowindow.open(map, marker);
                    }
                })(marker, customerMap));
            }
        }
	}
	google.maps.event.addDomListener(window, 'load', initialize);

    function generateContent(customerMap)
    {
        var content = `
            <div class="gd-bubble" style="">
                <div class="gd-bubble-inside">
                    <div class="geodir-bubble_desc">
                    <div class="geodir-bubble_image">
                        <div class="geodir-post-slider">
                            <div class="geodir-image-container geodir-image-sizes-medium_large ">
                                <div id="geodir_images_5de53f2a45254_189" class="geodir-image-wrapper" data-controlnav="1">
                                    <div class="geodir-post-title">
                                        <h5 class="geodir-entry-title">
                                            <a href="{{ route('customer.show', '') }}/${customerMap.id}" title="View: ${customerMap.name}">${customerMap.name}</a>
                                        </h5>
                                    </div>
                                    <a href="{{ route('customer.show', '') }}/`+customerMap.id+`"><img src="${ customerMap.thumbnail ? customerMap.thumbnail : '{{asset('images/no-avatar.jpg')}}'}" alt="${customerMap.name}" class="align size-medium_large" width="300"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="geodir-bubble-meta-side">
                    <div class="geodir-output-location">
                    <div class="geodir-output-location geodir-output-location-mapbubble">
                        <div class="geodir_post_meta  geodir-field-post_title">
                            <span> ${customerMap.name}</span>
                        </div>
                        <div class="geodir_post_meta  geodir-field-address">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${customerMap.address}</span>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            </div>
            </div>`;

    return content;

    }
</script>
@endpush