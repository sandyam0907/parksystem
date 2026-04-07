<?php $this->load->view('layout/header', ['title' => 'Booking Details']); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
   .section-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 12px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 12px;                     
    padding: 6px 0;
    border-bottom: 1px dashed #e0e0e0;
    font-size: 14px;
}

.info-row:last-child {
    border-bottom: none;
}

.label {
    min-width: 150px;              
    font-weight: 500;
    color: #555;
}

.value {
    color: #000;
    word-break: break-word;
}

</style>

<div class="container-fluid mt-4 px-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Booking Details</h4>
        <a href="<?= site_url('user-bookings') ?>" class="btn btn-sm btn-outline-secondary">
            ← Back
        </a>
    </div>

    <!-- TOP SECTION -->
    <div class="row g-3 mb-3">

        <!-- GENERAL DETAILS -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title text-center fw-bold fs-5">General Details</div>

                    <div class="info-row">
                        <span class="label">Booking Ref</span>
                        <span class="value"><?= $booking->booking_ref ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Created At</span>
                        <span class="value"><?= date('d M Y h:i A', strtotime($booking->created_at)) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Status</span>
                        <span class="badge bg-success"><?= ucfirst($booking->booking_status) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- USER DETAILS -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title text-center fw-bold fs-5">User Details</div>

                    <div class="info-row">
                        <span class="label">Name</span>
                        <span class="value"><?= $booking->user_name ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Username</span>
                        <span class="value"><?= $booking->username ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Email</span>
                        <span class="value"><?= $booking->user_email ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Phone</span>
                        <span class="value"><?= $booking->user_phone ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- MAP + PARKING DETAILS -->
    <div class="row g-3 mb-3">

        <!-- MAP -->
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="section-title text-center fw-bold fs-5">Map View</div>
                    <div id="parkingMap" style="height:320px;border-radius:8px;"></div>
                </div>
            </div>
        </div>

        <!-- PARKING DETAILS -->
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title text-center fw-bold fs-5">Parking Details</div>

                    <div class="info-row">
                        <span class="label">Parking Name</span>
                        <span class="value"><?= $booking->parking_name ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Area</span>
                        <span class="value"><?= $booking->area ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Address</span>
                        <span class="value"><?= $booking->parking_location ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Price / Hr</span>
                        <span class="value">₹<?= $booking->parking_price ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- VEHICLE + BOOKING INFO -->
    <div class="row g-3">

        <!-- VEHICLE -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="section-title text-center fw-bold fs-5 ">Vehicle Information</div>

                    <div class="info-row">
                        <span class="label">Vehicle No</span>
                        <span class="value"><?= $booking->vehicle_number ?: '-' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Brand</span>
                        <span class="value"><?= $booking->vehicle_brand ?: '-' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Model</span>
                        <span class="value"><?= $booking->vehicle_model ?: '-' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOOKING INFO -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="section-title text-center fw-bold fs-5">Booking Information</div>

                    <div class="info-row">
                        <span class="label">Booking Date</span>
                        <span class="value"><?= date('d M Y', strtotime($booking->booked_date)) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Time</span>
                        <span class="value"><?= $booking->start_time ?> - <?= $booking->end_time ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Duration</span>
                        <span class="value"><?= $booking->parking_duration ?> hrs</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Total Amount</span>
                        <span class="value">₹<?= $booking->total_amount ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- MAP SCRIPT -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var lat = <?= $booking->latitude ?>;
    var lng = <?= $booking->longitude ?>;

    var map = L.map('parkingMap').setView([lat, lng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([lat, lng])
        .addTo(map)
        .bindPopup(
            "<b><?= addslashes($booking->parking_name) ?></b><br><?= addslashes($booking->parking_location) ?>"
        )
        .openPopup();
</script>

<?php $this->load->view('layout/footer'); ?>
