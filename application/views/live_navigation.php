<!DOCTYPE html>
<html>
<head>
<title>Live Navigation</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<style>
#map { height: 100vh; width:100%; }
</style>
</head>
<body>
<div id="infoBox" style="
position:fixed;
top:10px;
left:50%;
transform:translateX(-50%);
z-index:9999;
background:white;
padding:10px 20px;
border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,0.2);
font-size:16px;
font-weight:bold;
">
Distance: -- | Time: --
</div>


<div id="map"></div>
<script>
var map = L.map('map').setView([12.9716, 77.5946], 13);

// OSM tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

// Destination (Whitefield example)
var destination = L.latLng(12.9381436, 77.7015219);

var userMarker;
var routingControl;

// Live GPS tracking
navigator.geolocation.watchPosition(function(pos) {

    var lat = pos.coords.latitude;
    var lng = pos.coords.longitude;

    var userLatLng = L.latLng(lat, lng);

    if (!userMarker) {

        userMarker = L.marker(userLatLng).addTo(map).bindPopup("You");
        map.setView(userLatLng, 16);

        routingControl = L.Routing.control({
            waypoints: [
                userLatLng,
                destination
            ],
            routeWhileDragging: false,
            show: false,
            addWaypoints: false,
            draggableWaypoints: false,
            lineOptions: {
                styles: [{color: 'blue', weight: 6}]
            },
            createMarker: function() { return null; } // hide default markers
        }).addTo(map);

        // 🧠 When route is found
        routingControl.on('routesfound', function(e) {
            var route = e.routes[0];

            var distance = (route.summary.totalDistance / 1000).toFixed(2); // KM
            var time = route.summary.totalTime; // seconds

            var minutes = Math.round(time / 60);

            document.getElementById("infoBox").innerHTML =
                "Distance: " + distance + " km | ETA: " + minutes + " min";
        });

    } else {

        userMarker.setLatLng(userLatLng);

        routingControl.setWaypoints([
            userLatLng,
            destination
        ]);
    }

}, function(err) {
    alert("GPS error: " + err.message);
}, {
    enableHighAccuracy: true,
    maximumAge: 0,
    timeout: 5000
});
</script>
<!--
<script>
var map = L.map('map').setView([12.9716, 77.5946], 13); // Bangalore

// OSM Tile
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

// Destination (example: Whitefield)
var destination = L.latLng(12.9381436, 77.7015219);

var userMarker;
var routingControl;

// Live GPS tracking
navigator.geolocation.watchPosition(function(pos) {

    var lat = pos.coords.latitude;
    var lng = pos.coords.longitude;

    var userLatLng = L.latLng(lat, lng);

    if (!userMarker) {
        userMarker = L.marker(userLatLng).addTo(map).bindPopup("You");
        map.setView(userLatLng, 15);

        // Create route
        routingControl = L.Routing.control({
            waypoints: [
                userLatLng,
                destination
            ],
            routeWhileDragging: false,
            show: false,
            addWaypoints: false,
            lineOptions: {
                styles: [{color: 'blue', weight: 6}]
            }
        }).addTo(map);

    } else {
        userMarker.setLatLng(userLatLng);

        // Update route dynamically
        routingControl.setWaypoints([
            userLatLng,
            destination
        ]);
    }

}, function(err) {
    alert("GPS error: " + err.message);
}, {
    enableHighAccuracy: true,
    maximumAge: 0,
    timeout: 5000
});
</script>
-->
</body>
</html>
