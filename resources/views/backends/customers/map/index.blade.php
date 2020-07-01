@extends('backends.layouts.master')
@section('title', __('customer.title'))
@section('content')
@push("header-style")
<link rel="stylesheet" href="//unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
<style>
    html, body{
        font-family: "KhmerOSBattambang-Regular", Helvetica, Arial, sans-serif !important;
    }
    .map-customer {
        position: relative;
        margin-top: -15px;
    }
    .sidebar-map {
        margin-top: 10px;
        padding-right: 10px;
        max-height: 100vh;
        overflow: hidden;
        overflow-y: auto;
    }
    .sidebar-map .table-bordered{
        border:none;
    }
    .sidebar-map tr td{
        padding: 0.5rem 0.75rem;
        border-left: 0px !important;
        border-right: 0px !important;
        font-size:13px;
        cursor: pointer;
    }
    .sidebar-map tr td:hover{
        opacity: 0.5;
    }
    .sidebar-map tr td:first-child{
        border-top: 0px !important;
    }
    .sidebar-map tr td:last-child{
        border-top: 0px !important;
    }
    .sidebar-map tr td:before {
        content: "\f7f2";
        font-family: 'Font Awesome\ 5 Free';
        font-weight: 900;
        margin-right: 5px;
    }
    .sidebar-map tr td.type1:before{
        color: #36b9cc;
    }
    .sidebar-map tr td.type2:before{
        color: #f6c23e;
    }
    .sidebar-map tr td.type3:before{
        color: #1cc88a;
    }
    .leaflet-popup-content {
        margin: 13px 19px;
        line-height: 1.4;
        max-width: 350px;
        width: 300px!important;
    }
    .list-group{
        max-height: 300px;
        overflow: hidden;
        overflow-y: auto;
    }
    .list-group-item {
        border: none;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125) !important;
        padding: 0.3rem 0.7rem !important;
    }
    .list-group-item:last-child {
        border-bottom-right-radius: 0px;
        border-bottom-left-radius: 0px;
        border-bottom: 0px;
    }
    .list-group-item:first-child {
        border-top-left-radius: 0px;
        border-top-right-radius: 0px;
    }
    .staff-name {
        font-weight: bold;
        text-transform: uppercase;
    }
    @media (max-width: 767px) {
        .sidebar-map {
            max-height: 200px;
        }
    }
</style>
@endpush
<div class="map-customer">
    <div class="row">
        <div class="col-12 col-md-2 px-0">
            <div class="input-group mb-3 pr-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-life-ring"></i></span>
                </div>
                <input class="form-control" id="range" type="number" value='0' min='0' step="10"/>
            </div>
            <div class="sidebar-map">
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
<script src="//ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.js"></script>
<script defer>
	var map = L.map('map-canvas-customer').setView([
            {{ $latitude }}, 
            {{$longitude}} 
        ], 16);
    var OpenStreetMap_Mapnik = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

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
        var content = `<div class="row">
        <div class="col-4 col-md-3 mb-2 px-0">
            <a href="{{ route('customer.show', '') }}/${customerMap.id}" target="_blank"><img src="${ customerMap.thumbnail ? customerMap.thumbnail : '{{asset('images/no-avatar.jpg')}}'}" alt="${customerMap.name}" class="align size-medium_large" width="300" style="max-width:100%"></a>
        </div>
        <div class="col-8 col-md-9">
            <h5><a href="{{ route('customer.show', '') }}/${customerMap.id}" target="_blank">${customerMap.customer_type.name} ${customerMap.name}</a></h5>
            <p><i class="fas fa-map-marker-alt"></i> <span>${customerMap.address}</span></p>
        </div>
        `;
        if(customerMap.sales && customerMap.sales[0] && customerMap.sales[0].staff) {
            content +=`<div class="staff-name">លេខកូដវិក្កយបត្រ</div>`;
        }
        for(sale in customerMap.sales) {
            sale = customerMap.sales[sale];
            content +=`<ul class="list-group w-100">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('sale.index') }}?quotaion_no=${sale.id}" target="_blank">
                            <i class="far fa-user"></i> ${sale.staff.name} ${sale.quotaion_no}
                        </a>
                    </li>
                    </ul>`;
        }
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
@endpush