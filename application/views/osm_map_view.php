<!DOCTYPE html>
<html>
<head>
    <title>Route: Panathur to Whitefield (OpenStreetMap)</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>

<h3>Route from Panathur to Whitefield (FREE Map)</h3>

<div id="map"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    var fromLat = <?= $from_lat ?>;
    var fromLng = <?= $from_lng ?>;

    var toLat = <?= $to_lat ?>;
    var toLng = <?= $to_lng ?>;

    // Initialize map
    var map = L.map('map').setView([fromLat, fromLng], 13);

    // Load OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Markers
    var fromMarker = L.marker([fromLat, fromLng]).addTo(map).bindPopup("Panathur").openPopup();
    var toMarker   = L.marker([toLat, toLng]).addTo(map).bindPopup("Whitefield");

    // Draw line (route)
    var routeLine = L.polyline([
        [fromLat, fromLng],
        [toLat, toLng]
    ], {
        weight: 5
    }).addTo(map);

    // Fit map to route
    map.fitBounds(routeLine.getBounds());
</script>

</body>
</html>
