@extends('backends.layouts.master')
@section('title', __('customer.title'))
@section('content')
@push("header-style")
<link rel="stylesheet" href="//unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.72.0/dist/L.Control.Locate.min.css" />
<link href="{{ asset('css/map.css') }}" rel="stylesheet">
<style>
.leaflet-popup-content p{
    margin: 7px 0;
}
.checkin{
    cursor: pointer;
}
#alert-success {
    position: absolute;
    z-index: 999;
    top: 10px;
    right: 10px;
}
</style>
@endpush
<button  class="btn btn-circle btn-danger bn-sp" onclick="openNav()">
    <i class="fas fa-plus"></i>
</button>
<div class="map-customer">
    <div class="row">
        <div class="col-12 col-md-2 px-1">
            @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleView() || Auth::user()->isRoleEditor())
            <div class="mt-1 mb-3 px-1">
                <a href="{{route('map.gps.staff')}}" class="btn btn-circle btn-primary w-100"><i class="fas fa-map-marker-alt mr-1"></i> GPSបុគ្គលិក</a>
            </div>
            @endif
            <div class="input-group mb-3 px-1">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-arrows-alt"></i></span>
                </div>
                <input class="form-control" id="range" type="number" value='0' min='0' step="10"/>
            </div>
            <div id="menu-plus" class="sidebar-map">
                <table class="table table-bordered">
                    <tbody id="t_points"></tbody>
                </table>
            </div>
        </div>
        <div class="col-12 col-md-10 px-0">
            <div id="map-canvas-customer"style="height: 100vh; width: 100%; position: relative; overflow: hidden;"></div>
        </div>
    </div>
</div>
<div id="alert-success"></div>
@endsection
@push('footer-script')
<script src="//polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="//unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
<script src="//ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.js"></script>
<script src="//cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.72.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>
<script defer>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // please replace this with your own mapbox token!
    var token = "pk.eyJ1Ijoia2Frcm9uYSIsImEiOiJja2NpcGF3cmsxMWlvMnhxcXZiajVvYno1In0.OdwnfS9n5Wv9qPQCtI-loA";
    var mapboxUrl = 'https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/{z}/{x}/{y}@2x?access_token=' + token;
    var mapboxAttrib = 'Map data © <a href="http://osm.org/copyright">OpenStreetMap</a> contributors. Tiles from <a href="https://www.mapbox.com">Mapbox</a>.';
    var mapbox = new L.TileLayer(mapboxUrl, {
        attribution: mapboxAttrib,
        tileSize: 512,
        zoomOffset: -1
    });
    // map customer
    var map = new L.Map('map-canvas-customer', {
        layers: [mapbox],
        center: [{{ $latitude }}, {{$longitude}} ],
        zoom: 10,
        zoomControl: true
    });

    var OpenStreetMap_Mapnik = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    
    var lc = L.control.locate({
        strings: {
            title: "My local"
        },
        showPopup: false
    }).addTo(map);
    function onLocationFound(e) {
        var radius = e.accuracy;
        L.circle(e.latlng, radius).addTo(map);
        $.ajax({
            url     : "{{route('map.gps')}}",
            method  : "POST",
            data    : {
                "_token": "{{ csrf_token() }}",
                "latitude": e.latitude,
                "longitude": e.longitude
            },
            dataType: 'json',
            success : function (json) {
            },
            error: function(json){
                $("html, body").animate({ scrollTop: 0 }, 500);
            }
        });
    }   
    map.on('locationfound', onLocationFound);
    function addRowTable(code, coords, type){
        var tr = document.createElement("tr");
        var td = document.createElement("td");
        td.className = `type${type}`;
        td.textContent = code;
        tr.appendChild(td);
        tr.onclick = function(){map.flyTo(coords, 17);};
        document.getElementById("t_points").appendChild(tr);
    }
    var buffers = [];
    function addMarker(name,lat,lng, customerMap){
        var p = L.marker([lat,lng])
            .bindTooltip(name)
            .openTooltip();
        p.title = name;
        p.alt = name;
        let content = generateContent(customerMap);
        p.bindPopup(content, {maxWidth : 560});
        p.addTo(map);
        addRowTable(name, [lat,lng], customerMap.customer_type_id);
        var c = L.circle([lat,lng], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 0
        }).addTo(map);
        buffers.push(c);
    }

    function generateContent(customerMap)
    {
        let phone2 = customerMap.phone2 ? `-${customerMap.phone2}` : '';
        let latitude = customerMap.latitude;
        let longitude = customerMap.longitude;
        let customerId = customerMap.id;

        var content = `<div class="row">
        <div class="col-4 col-md-3 mb-2 px-0">
            <img src="${ customerMap.thumbnail ? customerMap.thumbnail : '{{asset('images/no-avatar.jpg')}}'}" alt="${customerMap.name}" class="align size-medium_large" width="300" style="max-width:100%">
        </div>
        <div class="col-8 col-md-9">
            <h5>${customerMap.customer_type.name} ${customerMap.name}</h5>
            <p><i class="fas fa-map-pin"></i> <span>${customerMap.address}</span></p>`;
            if(customerMap.phone1){
             content += `<p><i class="fas fa-phone-square-alt text-success my-1 mr-1"></i>${customerMap.phone1}${phone2}</p>`;
            }
        content +=`<p onclick="mapCheckIn(${customerId}, ${latitude},${longitude} )" class="checkin"><i class="fas fa-map-marker-alt text-danger"></i> checkin</p>`;
        content +=`</div>`;
        @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleView() || Auth::user()->isRoleEditor())
        if(customerMap.sales && customerMap.sales[0] && customerMap.sales[0].staff) {
            content +=`<div class="staff-name">លេខកូដវិក្កយបត្រ</div>`;
        }
        for(sale in customerMap.sales) {
            sale = customerMap.sales[sale];
            content +=`<ul class="list-group w-100">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('sale.index') }}?quotaion_no=${sale.id}">
                            <i class="far fa-user"></i> ${sale.staff.name} ${sale.quotaion_no}
                        </a>
                    </li>
                    </ul>`;
        }
        @endif
        content +`</div>`;
        return content;
    }

    function mapCheckIn(latitude, longitude){
        $.ajax({
            url     : "{{route('map.gps')}}",
            method  : "POST",
            data    : {
                "_token": "{{ csrf_token() }}",
                "cusstomre_id" : customerId,
                "latitude": latitude,
                "longitude": longitude
            },
            dataType: 'json',
            success : function (json) {
                if(json.code == 200){
                    let html = `<div class="alert alert-success alert-dismissible fade show" id="checksuccess">
                            <strong><i class="fas fa-info-circle"></i> ${json.message}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>`;
                    $("#alert-success").html(html);

                    window.setTimeout(function() {
                        $("#checksuccess").fadeTo(500, 0).slideUp(500, function() {
                            $(this).remove();
                        });
                    }, 5000);
                }
            },
            error: function(json){
                $("html, body").animate({ scrollTop: 0 }, 500);
            }
        });
    }
    $(document).ready(function (){
        var customerMaps = @json($customerMaps);
        for(customerMap in customerMaps) {
            customerMap = customerMaps[customerMap];
            if(customerMap.latitude && customerMap.longitude)
            {
                let name = `${customerMap.customer_type.name} ${customerMap.name} `;
                addMarker(name, parseFloat(customerMap.latitude), parseFloat(customerMap.longitude), customerMap);
            }
        }
    });

    L.control.scale({maxWidth:240, metric:true, position: 'bottomleft'}).addTo(map);

    L.control.polylineMeasure({position:'topleft', imperial:false, clearMeasurementsOnStop: false, showMeasurementsClearControl: true}).addTo(map);


    $("#range").change(function(e){
    var radius = parseInt($(this).val())
        buffers.forEach(function(e){
            e.setRadius(radius);
            e.addTo(map);
        });
    });
</script>
<script>
    function openNav() {
        $("#menu-plus").toggleClass( "active" );
    }
</script>
@endpush