@extends('backends.layouts.master')
@section('title', __('customer.title'))
@section('content')
@push("header-style")
<link rel="stylesheet" href="//unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.72.0/dist/L.Control.Locate.min.css" />
<link href="{{ asset('css/map.css') }}" rel="stylesheet">
@endpush
<div class="map-customer">
    <div class="row">
        <div class="col-12 px-0">
            <div id="map-canvas-customer"style="height: 100vh; width: 100%; position: relative; overflow: hidden;"></div>
        </div>
    </div>
</div>
@endsection
@push('footer-script')
<script src="//polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="//unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
<script src="//cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.72.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>
<script defer>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	var map = L.map('map-canvas-customer').setView([
            {{ $latitude }}, 
            {{$longitude}} 
        ], 16);
    var OpenStreetMap_Mapnik = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    
    var LeafIcon = L.Icon.extend({
        options: {
            iconUrl: '{{asset('/images/user.svg')}}',
            iconSize: [68, 68]
        } 
    });

	var icon = new LeafIcon({iconUrl: '{{asset('/images/user.svg')}}'});
    function addMarker(name,lat,lng, customerMap){
        var p = L.marker([lat,lng], {icon: icon})
            .bindTooltip(name)
            .openTooltip();
        p.title = name;
        p.alt = name;
        let content = generateContent(customerMap);
        p.bindPopup(content).openPopup();
        p.addTo(map);
    }

    function generateContent(customerMap)
    {
        var content = `<div class="row">
            <div class="col-4 col-md-3 mb-2 px-0">
                <a href="{{ route('customer.show', '') }}/${customerMap.id}" target="_blank"><img src="${ customerMap.thumbnail ? customerMap.thumbnail : '{{asset('images/no-avatar.jpg')}}'}" alt="${customerMap.name}" class="align size-medium_large" width="300" style="max-width:100%"></a>
            </div>
            <div class="col-8 col-md-9">
                <h5><a href="{{ route('customer.show', '') }}/${customerMap.id}" target="_blank">${customerMap.customer_type.name} ${customerMap.name}</a></h5>
                <p><i class="fas fa-map-marker-alt"></i> <span>${customerMap.address}</span></p>
            </div>
            `;
        content +`</div>`;
        return content;
    }

    $(document).ready(function (){
        var customerMaps = @json($customerMaps);
        for(customerMap in customerMaps) {
            customerMap = customerMaps[customerMap];
            if(customerMap.latitude && customerMap.longitude)
            {
                let name = `${customerMap.customer_type.name} ${customerMap.name} `;
                addMarker(name, customerMap.latitude, customerMap.longitude, customerMap);
            }
        }
    });
</script>
@endpush