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
            iconSize: [32, 32]
        } 
    });

	var icon = new LeafIcon({iconUrl: '{{asset('/images/user.svg')}}'});
    function addMarker(name, lat, lng, staffMap){
        var p = L.marker([lat,lng], {icon: icon})
            .bindTooltip(name)
            .openTooltip();
        p.title = name;
        p.alt = name;
        let content = generateContent(staffMap);
        p.bindPopup(content).openPopup();
        p.addTo(map);
    }

    function generateContent(staffMap)
    {
        return "hello";
    }

    $(document).ready(function (){
        var staffMaps = @json($staffMaps);
        for(staffMap in staffMaps) {
            staffMap = staffMaps[staffMap];
            if(staffMap.latitude && staffMap.longitude)
            {
                let name = `${staffMap.customer_type.name} ${staffMap.name} `;
                addMarker(name, parseFloat(staffMap.latitude), parseFloat(staffMap.longitude), staffMap);
            }
        }
    });
</script>
@endpush