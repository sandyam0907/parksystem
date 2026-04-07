<!DOCTYPE html>
<html>
<head>
    <title>Route: Panathur to Whitefield</title>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>

<h3>Route from Panathur to Whitefield (Bangalore)</h3>

<div id="map"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKpJuAcNZwfgXQSVv4w3HjJhzM3R_RHiw"></script>

<script>
var map, directionsService, directionsRenderer;

function initMap() {

    var fromLocation = new google.maps.LatLng(<?= $from_lat ?>, <?= $from_lng ?>);
    var toLocation   = new google.maps.LatLng(<?= $to_lat ?>, <?= $to_lng ?>);

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: fromLocation
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();

    directionsRenderer.setMap(map);

    var request = {
        origin: fromLocation,
        destination: toLocation,
        travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, function(result, status) {
        if (status == 'OK') {
            directionsRenderer.setDirections(result);
        } else {
            alert("Route not found: " + status);
        }
    });
}

window.onload = initMap;
</script>

</body>
</html>
