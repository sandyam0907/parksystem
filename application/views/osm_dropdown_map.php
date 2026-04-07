<!DOCTYPE html>
<html>
<head>
    <title>Free Map Route with Dropdown (OSM)</title>

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

<h3>Route Finder (Free Map)</h3>

<form onsubmit="return false;">
    <label>From:</label>
    <select id="from_location">
        <?php foreach($locations as $key => $loc): ?>
            <option value="<?= $loc['lat'] ?>,<?= $loc['lng'] ?>">
                <?= $loc['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>To:</label>
    <select id="to_location">
        <?php foreach($locations as $key => $loc): ?>
            <option value="<?= $loc['lat'] ?>,<?= $loc['lng'] ?>">
                <?= $loc['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button onclick="showRoute()">Show Route</button>
</form>

<br>

<div id="map"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    var map = L.map('map').setView([12.9352, 77.6973], 13);

    // Load tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var fromMarker = null;
    var toMarker = null;
    var routeLine = null;

    function showRoute() {

        var fromVal = document.getElementById("from_location").value;
        var toVal   = document.getElementById("to_location").value;

        var from = fromVal.split(",");
        var to   = toVal.split(",");

        var fromLat = parseFloat(from[0]);
        var fromLng = parseFloat(from[1]);

        var toLat = parseFloat(to[0]);
        var toLng = parseFloat(to[1]);

        // Remove old markers & line
        if (fromMarker) map.removeLayer(fromMarker);
        if (toMarker) map.removeLayer(toMarker);
        if (routeLine) map.removeLayer(routeLine);

        // Add markers
        fromMarker = L.marker([fromLat, fromLng]).addTo(map).bindPopup("From").openPopup();
        toMarker   = L.marker([toLat, toLng]).addTo(map).bindPopup("To");

        // Draw route line
        routeLine = L.polyline([
            [fromLat, fromLng],
            [toLat, toLng]
        ], {
            weight: 5
        }).addTo(map);

        // Fit bounds
        map.fitBounds(routeLine.getBounds());
    }

    // Auto load first route
    showRoute();
</script>

</body>
</html>
