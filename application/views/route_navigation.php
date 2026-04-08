<!DOCTYPE html>
<html>

<head>
    <title>Live Navigation</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        #map {
            height: 100vh;
            width: 100%;
        }

        .suggestion-item:hover {
            background: #f0f0f0;
        }

        button:hover {
            opacity: 0.8;
        }
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
    font-weight:bold;
    ">
    Distance: -- | Time: --
    </div>


    <!-- <div id="suggestions" style="
    position:fixed;
    top:100px;
    left:50%;
    transform:translateX(-50%);
    z-index:9999;
    background:white;
    width:260px;
    border:1px solid #ccc;
    max-height:200px;
    overflow-y:auto;
    "></div> -->

    <div style="
    position:fixed;
    top:60px;
    left:50%;
    transform:translateX(-50%);
    z-index:9999;
    display:flex;
    align-items:center;
    gap:8px;
    ">

        <!-- Destination Input -->
        <!-- <input type="text" id="destInput" placeholder="Enter destination" oninput="searchLocation()" style="
            padding:10px;
            width:220px;
            border-radius:6px;
            border:1px solid #ccc;
            "> -->

        <!-- Refresh Button -->
        <button onclick="refreshRoutes()" title="Refresh Route" style="
        padding:10px;
        border:none;
        border-radius:6px;
        background:#3498db;
        color:white;
        cursor:pointer;">
        <i class="fas fa-rotate"></i>
        </button>

        <!--Reset Button -->
        <button onclick="resetNavigation()" title="Reset Navigation" style="
        padding:10px;
        border:none;
        border-radius:6px;
        background:#e74c3c;
        color:white;
        cursor:pointer;">
        <i class="fas fa-trash"></i>
        </button>

    </div>

    <div id="toastMsg" style="
    position:fixed;
    bottom:20px;
    left:50%;
    transform:translateX(-50%);
    background:#333;
    color:white;
    padding:10px 20px;
    border-radius:20px;
    font-size:14px;
    opacity:0;
    transition:opacity 0.3s;
    z-index:9999;">
    </div>

    <div id="map"></div>
</body>
</html>

 <!-- <script>

        var map = L.map('map').setView([12.9716, 77.5946], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        var userMarker = null;
        var routingControl = null;
        var destination = L.latLng(16.81085, 75.712968); // static
        var lastPosition = null;
        var routeLines = []; // To store custom clickable lines
        var allRoutesData = []; // To store route metadata (distance/time)
        function attachRouteListener(control) {
            control.on('routesfound', function (e) {
                allRoutesData = e.routes;
                console.log("Routes count:", allRoutesData.length);

                // 1. Remove any previous custom lines
                routeLines.forEach(line => map.removeLayer(line));
                routeLines = [];

                // 2. Create custom polylines for each route found
                allRoutesData.forEach(function (route, index) {
                    var isFirst = (index === 0);

                    var line = L.polyline(route.coordinates, {
                        color: isFirst ? 'blue' : 'gray',
                        weight: isFirst ? 7 : 5,
                        opacity: isFirst ? 0.8 : 0.5,
                        lineJoin: 'round'
                    }).addTo(map);

                    // 3. Add click listener to each line
                    line.on('click', function () {
                        selectRoute(index);
                    });

                    routeLines.push(line);
                });

                // Initialize display with first route
                updateInfoBox(allRoutesData[0]);
            });
        }
        function selectRoute(index) {
            routeLines.forEach((line, i) => {
                if (i === index) {
                    line.setStyle({ color: 'blue', weight: 8, opacity: 0.9 });
                    line.bringToFront();
                    updateInfoBox(allRoutesData[i]);
                } else {
                    line.setStyle({ color: 'gray', weight: 5, opacity: 0.5 });
                }
            });
            showMessage("Route " + (index + 1) + " selected ✅");
        }

        function updateInfoBox(route) {
            var distance = (route.summary.totalDistance / 1000).toFixed(2);
            var time = Math.round(route.summary.totalTime / 60);
            document.getElementById("infoBox").innerHTML =
                "Distance: " + distance + " km | ETA: " + time + " min";
        }


        // LOAD SAVED DESTINATION
        // window.onload = function () {
        //     var saved = sessionStorage.getItem("destination")

        //     if (saved) {
        //         var place = JSON.parse(saved);

        //         destination = L.latLng(parseFloat(place.lat), parseFloat(place.lon));
        //         document.getElementById("destInput").value = place.display_name;
        //     }
        // };

        // LIVE LOCATION
        navigator.geolocation.watchPosition(function (pos) {
            var lat = pos.coords.latitude;
            var lng = pos.coords.longitude;
            var userLatLng = L.latLng(lat, lng);

            if (!userMarker) {
                userMarker = L.marker(userLatLng).addTo(map).bindPopup("You");
                map.setView(userLatLng, 16);
                lastPosition = userLatLng;

                if (destination) {
                    routingControl = L.Routing.control({
                        waypoints: [userLatLng, destination],
                        router: L.Routing.osrmv1({
                            serviceUrl: 'https://router.project-osrm.org/route/v1',
                            alternatives: true // Request alternatives from OSRM
                        }),
                        lineOptions: {
                            // Hide the default routing line so our custom lines work
                            styles: [{ color: 'transparent', opacity: 0, weight: 0 }]
                        },
                        show: false,
                        addWaypoints: false,
                        createMarker: function () { return null; }
                    }).addTo(map);

                    attachRouteListener(routingControl);
                }
            } else {
                userMarker.setLatLng(userLatLng);
                var distanceMoved = userLatLng.distanceTo(lastPosition);
                var now = Date.now();

                if (distanceMoved > 20 && (now - (window.lastUpdateTime || 0) > 5000)) {
                    refreshRoute();
                    lastPosition = userLatLng;
                    window.lastUpdateTime = now;
                }
            }
        }, function (err) {
            alert("GPS error: " + err.message);
        }, { enableHighAccuracy: true });

        // SEARCH FUNCTION
        // function searchLocation() {

        //     var query = document.getElementById("destInput").value;

        //     if (query.length < 3) {
        //         document.getElementById("suggestions").innerHTML = "";
        //         return;
        //     }

        //     fetch("https://nominatim.openstreetmap.org/search?format=json&q=" + encodeURIComponent(query))
        //         .then(res => res.json())
        //         .then(data => {

        //             data.sort((a, b) => {
        //                 let aMatch = a.display_name.toLowerCase().includes(query.toLowerCase());
        //                 let bMatch = b.display_name.toLowerCase().includes(query.toLowerCase());
        //                 return bMatch - aMatch;
        //             });

        //             var box = document.getElementById("suggestions");
        //             box.innerHTML = "";

        //             data.slice(0, 5).forEach(function (place) {

        //                 var div = document.createElement("div");
        //                 div.className = "suggestion-item";
        //                 div.innerHTML = place.display_name;
        //                 div.style.padding = "8px";
        //                 div.style.cursor = "pointer";

        //                 div.onclick = function () {
        //                     selectLocation(place);
        //                 };

        //                 box.appendChild(div);
        //             });

        //         });

        // }

        // SELECT LOCATION
        // function selectLocation(place) {

        //     if (!userMarker) {

        //         alert("Wait for location...");
        //         return;
        //     }

        //     var lat = parseFloat(place.lat);
        //     var lon = parseFloat(place.lon);

        //     destination = L.latLng(lat, lon);

        //     sessionStorage.setItem("destination", JSON.stringify(place));

        //     document.getElementById("destInput").value = place.display_name;
        //     document.getElementById("suggestions").innerHTML = "";

        //     if (routingControl) {
        //         map.removeControl(routingControl);
        //     }

        //     routingControl = L.Routing.control({
        //         waypoints: [
        //             userMarker.getLatLng(),
        //             destination
        //         ],
        //         fitSelectedRoutes: true,
        //         show: true,
        //         position: 'topright',
        //         routeWhileDragging: true,
        //         alternatives: true,
        //         router: L.Routing.osrmv1({
        //             serviceUrl: 'https://router.project-osrm.org/route/v1'
        //         }),
        //         lineOptions: {
        //             styles: [{ color: 'blue', weight: 6 }]
        //         },
        //         createMarker: function () { return null; }
        //     }).addTo(map);

        //     attachRouteListener(routingControl);
        // }

        function showMessage(msg) {
            var toast = document.getElementById("toastMsg");
            toast.innerText = msg;
            toast.style.opacity = "1";
            setTimeout(() => { toast.style.opacity = "0"; }, 2000);
        }

        // REFRESH ROUTE
        function resetNavigation() {
            sessionStorage.removeItem("destination");
            if (routingControl) {
                map.removeControl(routingControl);
                routingControl = null;
            }
            routeLines.forEach(line => map.removeLayer(line));
            routeLines = [];
            destination = null;
            document.getElementById("infoBox").innerHTML = "Distance: -- | Time: --";
            showMessage("Navigation reset 🗑");
        }
        // RESET NAVIGATION
        function resetNavigation() {

            // clear storage
            sessionStorage.removeItem("destination");

            // clear input
            // document.getElementById("destInput").value = "";

            // clear suggestions
            // document.getElementById("suggestions").innerHTML = "";

            // remove route
            if (routingControl) {
                map.removeControl(routingControl);
                routingControl = null;
            }

            // reset destination
            destination = null;

            // reset info
            document.getElementById("infoBox").innerHTML = "Distance: -- | Time: --";

            showMessage("Navigation reset 🗑");
        }

    </script> -->

    <script>

        var map = L.map('map').setView([16.8222, 75.7209], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

        var destination = L.latLng(16.81085087840834, 75.71296882917306);
        L.marker(destination).addTo(map).bindPopup("Destination");

        var userMarker = null;
        var routeLines = [];
        var allRoutesData = [];
        var lastPosition = null;

        // --- 1. CORE MULTI-FETCH LOGIC ---
        async function getForcedRoutes(start, end) {
            // Clear previous lines
            routeLines.forEach(line => { if (line) map.removeLayer(line); });
            routeLines = [];
            allRoutesData = [];

            // URL 1: The standard "Fastest" route
            const url1 = `https://router.project-osrm.org/route/v1/driving/${start.lng},${start.lat};${end.lng},${end.lat}?alternatives=true&overview=full&geometries=geojson`;

            // URL 2: The "Forced Alternative"
            const detourPoint = {
                lat: (start.lat + end.lat) / 2 + 0.005,
                lng: (start.lng + end.lng) / 2 + 0.005
            };
            const url2 = `https://router.project-osrm.org/route/v1/driving/${start.lng},${start.lat};${detourPoint.lng},${detourPoint.lat};${end.lng},${end.lat}?overview=full&geometries=geojson`;

            try {
                const [res1, res2] = await Promise.all([
                    fetch(url1).then(r => r.json()),
                    fetch(url2).then(r => r.json())
                ]);
                console.log("OSRM routes:", res1.routes ? res1.routes.length : 0);
                console.log("Detour route:", res2.routes ? res2.routes.length : 0);

                let index = 0;

                // 🔹 1. Draw ALL OSRM routes
                if (res1.routes && res1.routes.length > 0) {
                    res1.routes.forEach(route => {
                        drawCustomRoute(route, index);
                        index++;
                    });
                }

                // 🔹 2. Add detour route ALSO
                if (res2.routes && res2.routes.length > 0) {
                    let detourRoute = res2.routes[0];

                    // simple check (optional)
                    if (res1.routes.length === 0 ||
                        detourRoute.distance !== res1.routes[0].distance) {
                        drawCustomRoute(detourRoute, index);
                    }
                }

            } catch (err) {
                console.error("Routing error:", err);
            }
        }

        // --- 2. DRAWING THE LINES ---
        function drawCustomRoute(route, index) {
            // OSRM GeoJSON is [lng, lat], Leaflet needs [lat, lng]
            var coords = route.geometry.coordinates.map(c => [c[1], c[0]]);
            var isPrimary = (index === 0);

            var colors = ['blue', 'green', 'orange', 'purple'];

            var line = L.polyline(coords, {
                color: colors[index] || '#717171',
                weight: index === 0 ? 7 : 5,
                opacity: index === 0 ? 0.9 : 0.6,

            }).addTo(map);

            line.on('click', function (e) {
                L.DomEvent.stopPropagation(e);
                selectRoute(index);
            });

            routeLines[index] = line;
            allRoutesData[index] = route;
            if (isPrimary) updateInfoUI(route);

        }

        // --- 3. SELECTION & UI ---
        function selectRoute(index) {
            routeLines.forEach((line, i) => {
                if (!line) return;
                if (i === index) {
                    line.setStyle({ color: 'blue', weight: 8, opacity: 0.9 });
                    line.bringToFront();
                    updateInfoUI(allRoutesData[i]);
                } else {
                    line.setStyle({ color: '#454545', weight: 10, opacity: 0.4 });
                }
            });
            showMessage("Route " + (index + 1) + " selected");
        }

        function updateInfoUI(route) {
            var dist = (route.distance / 1000).toFixed(2);
            var time = Math.round(route.duration / 60);
            document.getElementById("infoBox").innerHTML = `Distance: ${dist} km | Time: ${time} min`;
        }

        // --- 4. LIVE LOCATION ---
        navigator.geolocation.watchPosition(function (pos) {
            var userLatLng = L.latLng(pos.coords.latitude, pos.coords.longitude);

            if (!userMarker) {
                userMarker = L.marker(userLatLng).addTo(map).bindPopup("You");
                map.setView(userLatLng, 15);
                lastPosition = userLatLng;
                getForcedRoutes(userLatLng, destination);
            } else {
                userMarker.setLatLng(userLatLng);
                if (userLatLng.distanceTo(lastPosition) > 30) {
                    getForcedRoutes(userLatLng, destination);
                    lastPosition = userLatLng;
                }
            }
        }, null, { enableHighAccuracy: true });

        function showMessage(msg) {
            var toast = document.getElementById("toastMsg");
            toast.innerText = msg; toast.style.opacity = "1";
            setTimeout(() => { toast.style.opacity = "0"; }, 2000);
        }

        function refreshRoutes() {
            if (userMarker && destination) {

                let currentPos = userMarker.getLatLng();

                // call same function again
                getForcedRoutes(currentPos, destination);

                showMessage("Routes refreshed 🔄");

            } else {
                showMessage("Location not ready ⚠️");
            }
        }
    </script>