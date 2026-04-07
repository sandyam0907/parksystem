<!DOCTYPE html>
<html>
<head>
    <title>Free Address Autocomplete (Leaflet + OSM)</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <style>
        #map {
            height: 450px;
            width: 100%;
        }

        .suggestions {
            border: 1px solid #ccc;
            max-height: 200px;
            overflow-y: auto;
            position: absolute;
            background: #fff;
            width: 300px;
            z-index: 9999;
        }

        .suggestions div {
            padding: 8px;
            cursor: pointer;
        }

        .suggestions div:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>

<h3>Search Address (100% Free)</h3>

<div style="position:relative;">
    <input type="text" id="address" placeholder="Type location..." style="width:300px;padding:8px;">
    <div id="suggestions" class="suggestions" style="display:none;"></div>
</div>

<br>

Latitude: <input type="text" id="lat">
Longitude: <input type="text" id="lng">

<br><br>

<div id="map"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    var map = L.map('map').setView([12.9352, 77.6973], 13); // Bangalore

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = null;

    var addressInput = document.getElementById("address");
    var suggestionsDiv = document.getElementById("suggestions");

    addressInput.addEventListener("keyup", function () {
        var query = this.value;

        if (query.length < 3) {
            suggestionsDiv.style.display = "none";
            return;
        }

        fetch("https://nominatim.openstreetmap.org/search?format=json&q=" + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestionsDiv.innerHTML = "";

                if (data.length == 0) {
                    suggestionsDiv.style.display = "none";
                    return;
                }

                data.forEach(function (item) {
                    var div = document.createElement("div");
                    div.innerHTML = item.display_name;

                    div.onclick = function () {
                        selectLocation(item);
                    };

                    suggestionsDiv.appendChild(div);
                });

                suggestionsDiv.style.display = "block";
            });
    });

    function selectLocation(item) {
		

        var lat = item.lat;
        var lon = item.lon;

        document.getElementById("lat").value = lat;
        document.getElementById("lng").value = lon;
        document.getElementById("address").value = item.display_name;

        suggestionsDiv.style.display = "none";

        // Move map
        if (marker) {
            map.removeLayer(marker);
        }

        marker = L.marker([lat, lon]).addTo(map)
            .bindPopup(item.display_name)
            .openPopup();

        map.setView([lat, lon], 15);
    }
</script>

</body>
</html>
