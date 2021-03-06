@extends('backends.layouts.master')
@section('title', __('customer.title'))
@section('content')
@push("header-style")
<link rel="stylesheet" href="//unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.72.0/dist/L.Control.Locate.min.css" />
<link href="{{ asset('css/map.css') }}" rel="stylesheet">
<style>
.leaflet-popup-content {
    width: 150px !important;
    display: flex;
    justify-content: center;
    align-items: center;
}
.leaflet-container{
    font-family: "KhmerOSBattambang-Regular", Helvetica, Arial, sans-serif !important;
}
.leaflet-popup-content p {
    margin: 10px 0;
}
.thumbnail-cicel {
    box-shadow: 0 1px 5px 1px rgb(255 255 255), 0 1px 5px 1px rgb(255 255 255);
}
</style>
@endpush
<div class="row sp-staff-block">
    <div class="col-6 p-1">
        <div class="card shadow py-2 border-success">
            <div class="card-body text-center">
                <a href="{{route('sale.index')}}"><i class="far fa-newspaper text-warning"></i> {{__('menu.sale')}}</a>
            </div>
        </div>
    </div>
    <div class="col-6 p-1">
        <div class="card shadow py-2 border-success">
            <div class="card-body text-center">
                <a href="{{route('customer.index')}}"><i class="fas fa-users text-danger"></i> {{__('menu.customer')}}</a>
            </div>
        </div>
    </div>
    <div class="col-6 mb-4 p-1">
        <div class="card shadow py-2 border-success">
            <div class="card-body text-center">
                <a href="{{route('customer_map.index')}}"><i class="fas fa-map-marked-alt"></i> {{__('menu.customer_map')}}</a>
            </div>
        </div>
    </div>
</div>
<button  class="btn btn-circle btn-danger bn-sp" onclick="openNav()">
    <i class="fas fa-plus"></i>
</button>
<div class="map-customer staff-custom">
    <div class="row">
        <div class="col-12 col-md-2 px-1">
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
            {{ $longitude }}
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

    function addRowTable(code, coords, type, created){
        var tr = document.createElement("tr");
        var td = document.createElement("td");
        td.className = `type${type}`;
        td.textContent = `${code}:${created}`;
        tr.appendChild(td);
        tr.onclick = function(){map.flyTo(coords, 17);};
        document.getElementById("t_points").appendChild(tr);
    }

    var icon = new LeafIcon({iconUrl: '{{asset('/images/user.svg')}}'});
    var buffers = [];
    function addMarker(name, lat, lng, staffMap){
        var p = L.marker([lat,lng], {icon: icon})
            .bindTooltip(name)
            .openTooltip();
        p.title = name;
        p.alt = name;
        addRowTable(name, [lat,lng], staffMap.staff_id, staffMap.created_at);
        let content = generateContent(staffMap);
        p.bindPopup(content)
        .openPopup();
        p.addTo(map);
    }

    function generateContent(staffMap)
    {
        console.log(staffMap);
        let end_date_place = staffMap.end_date_place ? '-'+staffMap.end_date_place : '';
        let customerName = staffMap.customer ? staffMap.customer.name : '';
        var content = `<div class="text-center">
        <div class="thumbnail-cicel border-0 mb-1" style="width:100px; height:100px; margin: 0px auto;">
            <a href="{{ route('user.show', '') }}/${staffMap.id}" target="_blank"><img src="${ staffMap.thumbnail ? staffMap.thumbnail : '{{asset('images/no-avatar.jpg')}}'}" alt="${staffMap.name}" class="align size-medium_large" width="300" style="max-width:100%"></a>
        </div>
        <div style="text-align: left;">
        <p><a href="{{ route('user.show', '') }}/${staffMap.id}" target="_blank"><i class="fas fa-user-tie"></i> ${staffMap.name}</a></p>
        <p><a href="{{ route('customer.show', '') }}/${staffMap.customer_id}" target="_blank"><i class="fas fa-user-md"></i> ${customerName}</a></p>
        <div><i class="far fa-clock"></i> ${staffMap.start_date_place}${end_date_place}</div></div>
        </div>`;
        return content;
    }

    $(document).ready(function (){
        var staffMaps = @json($staffMaps);
        for(staffMap in staffMaps) {
            staffMap = staffMaps[staffMap];
            if(staffMap.staff_latitude && staffMap.staff_longitude)
            {
                let name = `${staffMap.staff.name}`;
                addMarker(name, parseFloat(staffMap.staff_latitude), parseFloat(staffMap.staff_longitude), staffMap);
            }
        }
    });
    function openNav() {
        $("#menu-plus").toggleClass( "active" );
    }
</script>
@endpush