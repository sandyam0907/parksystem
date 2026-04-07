<?php $this->load->view('layout/header', ['title' => 'Add Parking']); ?>
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
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Add Parking</h5>
                </div>

                <div class="card-body">
                    <?php if (validation_errors()): ?>
                        <div class="alert alert-danger">
                            <strong>Error!</strong><br>
                            <?= validation_errors('<div>', '</div>'); ?>
                        </div>
                    <?php endif; ?>


                    <form method="post" enctype="multipart/form-data" action="<?= site_url('parking/store') ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Parking Name</label>
                                <input type="text" name="parking_name" value="<?= set_value('parking_name') ?>"
                                    class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" name="parking_price" value="<?= set_value('parking_price') ?>"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slots</label>
                                <input type="number" name="parking_slots" value="<?= set_value('parking_price') ?>"
                                    class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Location</label>
                                <div style="position:relative; width:300px;">
                                    <input type="text" id="address" name="parking_location"
                                        placeholder="Type location..." class="form-control mb-1" autocomplete="off">

                                    <div id="suggestions" class="suggestions" style="display:none;"></div>
                                </div>

                                <div id="map" class="mt-2"></div>


                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Area</label>
                                <input type="text" name="area" value="<?= set_value('area') ?>" class="form-control" id="area">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pincode</label>
                                <input type="text" name="pincode" value="<?= set_value('pincode') ?>"
                                    class="form-control" id="pincode">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" value="<?= set_value('latitude') ?>" class="form-control" id="lat">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" value="<?= set_value('longitude') ?>"class="form-control" id="lng">
                            </div>
                        </div>
                        <div class="row">
    <div class="col-md-12 mb-3">
        <label class="form-label">Parking Image</label>
        <input type="file" name="parking_image" class="form-control" accept="image/*">
    </div>
</div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('parking') ?>" class="btn btn-secondary">
                                Back
                            </a>
                            <button type="submit" class="btn btn-success">
                                Save Parking
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>
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
		 // Fetch address details (including pincode)
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
            .then(res => res.json())
            .then(data => {
                if (data.address) {
                    document.getElementById("pincode").value = data.address.postcode || '';
					document.getElementById("area").value = data.address.suburb || '';
                    console.log("Full Address:", data.address);
                }
            });
			 

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
