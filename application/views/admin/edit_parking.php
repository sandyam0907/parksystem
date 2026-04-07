<?php $this->load->view('layout/header', ['title' => 'Edit Parking']); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    #map {
        height: 400px;
        width: 100%;
        border-radius: 6px;
    }

    .suggestions {
        border: 1px solid #ccc;
        max-height: 200px;
        overflow-y: auto;
        position: absolute;
        background: #fff;
        width: 100%;
        z-index: 9999;
        border-radius: 4px;
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
    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0 text-center">Edit Parking</h5>
        </div>

        <div class="card-body">

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger">
                    <?= validation_errors() ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('parking/update/' . $parking->park_id) ?>" enctype="multipart/form-data">

                <!-- ================= BASIC DETAILS ================= -->
                <h6 class="text-primary">Basic Details</h6>
                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Parking Name</label>
                        <input type="text" name="parking_name" value="<?= $parking->parking_name ?>" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Price</label>
                        <input type="number" name="parking_price" value="<?= $parking->parking_price ?>" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Slots</label>
                        <input type="number" name="parking_slots" value="<?= $parking->parking_slots ?>" class="form-control">
                    </div>
                </div>

                <!-- ================= LOCATION ================= -->
                <h6 class="text-primary mt-4">Parking Location</h6>
                <p class="text-muted mb-2">Search a new place or drag the marker to update the exact parking location.</p>
                <hr>

                <div class="row position-relative">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Search New Location</label>
                        <input type="text" id="edit_address" class="form-control"
                               placeholder="Type area, city, landmark...">
                        <div id="edit_suggestions" class="suggestions" style="display:none;"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div id="map"></div>
                    </div>
                </div>

                <div class="bg-light p-3 rounded">
                    <h6 class="text-secondary">Auto-filled Location Details</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Full Address</label>
                            <input type="text" name="parking_location" value="<?= $parking->parking_location ?>" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Area</label>
                            <input type="text" name="area" value="<?= $parking->area ?>" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Pincode</label>
                            <input type="text" name="pincode" value="<?= $parking->pincode ?>" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Latitude</label>
                            <input type="text" name="latitude" value="<?= $parking->latitude ?>" class="form-control" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Longitude</label>
                            <input type="text" name="longitude" value="<?= $parking->longitude ?>" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <!-- ================= IMAGE ================= -->
                <h6 class="text-primary mt-4">Parking Image</h6>
                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3 text-center">
                        <label>Current Image</label><br>
                        <?php if ($parking->parking_image): ?>
                            <img src="<?= base_url('uploads/parking/' . $parking->parking_image) ?>"
                                 class="img-fluid mt-2"
                                 style="max-width:140px; border-radius:6px;">
                        <?php else: ?>
                            <p class="text-muted mt-2">No image uploaded</p>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Change Image</label>
                        <input type="file" name="parking_image" class="form-control">
                        <small class="text-muted">Upload only if you want to replace the existing image</small>
                    </div>
                </div>

                <!-- ================= ACTIONS ================= -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= site_url('parking') ?>" class="btn btn-secondary">
                        Back
                    </a>
                    <button class="btn btn-success">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const lat = <?= $parking->latitude ?>;
    const lng = <?= $parking->longitude ?>;

    const map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = L.marker([lat, lng], { draggable: true })
        .addTo(map)
        .bindPopup("Drag or search to update parking location")
        .openPopup();

    marker.on('dragend', function (e) {
        const pos = e.target.getLatLng();
        updateLocation(pos.lat, pos.lng);
    });

    const editInput = document.getElementById("edit_address");
    const editSuggestions = document.getElementById("edit_suggestions");
    let timer = null;

    editInput.addEventListener("keyup", function () {
        const q = this.value.trim();
        if (q.length < 3) {
            editSuggestions.style.display = "none";
            return;
        }

        clearTimeout(timer);
        timer = setTimeout(() => {
            fetch("<?= site_url('parking/search_location') ?>?q=" + encodeURIComponent(q))
                .then(res => res.json())
                .then(data => {
                    editSuggestions.innerHTML = "";
                    data.forEach(item => {
                        if (!item.lat || !item.lon) return;
                        const div = document.createElement("div");
                        div.textContent = item.display_name;
                        div.onclick = () => {
                            marker.setLatLng([item.lat, item.lon]);
                            map.setView([item.lat, item.lon], 15);
                            updateLocation(item.lat, item.lon, item.display_name);
                            editSuggestions.style.display = "none";
                            editInput.value = item.display_name;
                        };
                        editSuggestions.appendChild(div);
                    });
                    editSuggestions.style.display = "block";
                });
        }, 400);
    });

    function updateLocation(lat, lon, name = '') {
        document.querySelector('input[name="latitude"]').value = lat;
        document.querySelector('input[name="longitude"]').value = lon;

        fetch("<?= site_url('parking/reverse_location') ?>?lat=" + lat + "&lon=" + lon)
            .then(res => res.json())
            .then(data => {
                if (data.address) {
                    document.querySelector('input[name="parking_location"]').value =
                        name || data.display_name || '';

                    document.querySelector('input[name="area"]').value =
                        data.address.suburb ||
                        data.address.city ||
                        data.address.town ||
                        '';

                    document.querySelector('input[name="pincode"]').value =
                        data.address.postcode || '';
                }
            });
    }
</script>
