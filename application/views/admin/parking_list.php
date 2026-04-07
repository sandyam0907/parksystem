<?php $this->load->view('layout/header', ['title' => 'Employee']); ?>
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Parking List</h4>
        <a href="<?= site_url('parking/add') ?>" class="btn btn-primary">+ Add Parking</a>
    </div>

    <div class="table-responsive">
        <table id="myTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Slots</th>
                    <th>Location</th>
                    <th>Area</th>
                    <th>Pincode</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($parkings as $p): ?>
                    <tr>
                        <td><?= $p->park_id ?></td>
                        <td>
                            <?php if (!empty($p->parking_image)): ?>
                                <img src="<?= base_url('uploads/parking/' . $p->parking_image) ?>" width="50" height="40"
                                    style="object-fit:cover;border-radius:4px;">
                            <?php else: ?>
                                <small>No Image</small>
                            <?php endif; ?>
                        </td>

                        <td><?= $p->parking_name ?></td>
                        <td>₹ <?= $p->parking_price ?></td>
                        <td><?= $p->parking_slots ?></td>
                        <td><?= $p->parking_location ?></td>
                        <td><?= $p->area ?></td>
                        <td><?= $p->pincode ?></td>
                        <td><?= $p->latitude ?></td>
                        <td><?= $p->longitude ?></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input parking-status-switch" type="checkbox"
                                    data-id="<?= $p->park_id ?>" <?= $p->parking_status === 'open' ? 'checked' : '' ?>>
                                <label class="form-check-label status-label">
                                    <?= $p->parking_status === 'open' ? 'Open' : 'Close' ?>
                                </label>
                            </div>
                        </td>


                        <td>
                             <a href="<?= site_url('parking/edit/' . $p->park_id) ?>" class="btn btn-sm btn-warning">Edit</a>

                            <a href="<?= site_url('parking/delete/' . $p->park_id) ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure to delete?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Parking Modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Edit Parking</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="modalError"></div>
                <form id="editForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id">
                    Parking_Name:   <input name="parking_name" id="parking_name" class="form-control mb-2">
                    Parking_Price:  <input name="parking_price" id="parking_price" class="form-control mb-2">
                    Parking_Slots:  <input name="parking_slots" id="parking_slots" class="form-control mb-2">
                    Parking_Location:   <input name="parking_location" id="parking_location" class="form-control mb-2">
                    Area:   <input name="area" id="area" class="form-control mb-2">
                    Pincode:    <input name="pincode" id="pincode" class="form-control mb-2">
                    Latitude:   <input name="latitude" id="latitude" class="form-control mb-2">
                    Longitude:  <input name="longitude" id="longitude" class="form-control mb-2">
                    <div class="mb-2 text-center">
                        <img id="edit_image_preview" src="" width="120" style="display:none;border-radius:6px;">
                    </div>

                    <input type="file" name="parking_image" id="parking_image" class="form-control mb-2">


                    <button class="btn btn-primary w-100">Update</button>
                </form>
            </div>

        </div>
    </div>
</div>


<?php $this->load->view('layout/footer'); ?>

<script>
    $(document).ready(function () {

        /* ===============================
           STATUS TOGGLE (AJAX)
        =============================== */
        $(document).on('change', '.parking-status-switch', function () {

            let parkingId = $(this).data('id');
            let newStatus = $(this).is(':checked') ? 'open' : 'close';

            $.ajax({
                url: "<?= site_url('parking/update_status') ?>",
                type: "POST",
                data: {
                    id: parkingId,
                    status: newStatus
                },
                success: function (res) {
                    let r = JSON.parse(res);
                    if (!r.status) {
                        alert('Status update failed');
                    }
                },
                error: function () {
                    alert('Something went wrong');
                }
            });
        });


        /* ===============================
           EDIT BUTTON (OPEN MODAL)
           ✅ event delegation (DataTables safe)
        =============================== */
        $(document).on('click', '.editBtn', function () {

            let id = $(this).data('id');

            $.get("<?= site_url('parking/get_parking') ?>/" + id, function (res) {

                let p = JSON.parse(res);

                $('#edit_id').val(p.park_id);
                $('#parking_name').val(p.parking_name);
                $('#parking_price').val(p.parking_price);
                $('#parking_slots').val(p.parking_slots);
                $('#parking_location').val(p.parking_location);
                $('#area').val(p.area);
                $('#pincode').val(p.pincode);
                $('#latitude').val(p.latitude);
                $('#longitude').val(p.longitude);

                // ✅ IMAGE PREVIEW
                if (p.parking_image) {
                    $('#edit_image_preview')
                        .attr('src', '<?= base_url('uploads/parking/') ?>' + p.parking_image)
                        .show();
                } else {
                    $('#edit_image_preview').hide();
                }

                $('#editModal').modal('show');
            });
        });


        /* ===============================
           EDIT FORM SUBMIT (AJAX + IMAGE)
           ❌ NO serialize()
           ✅ FormData REQUIRED
        =============================== */
        $(document).on('submit', '#editForm', function (e) {
            e.preventDefault();

            let id = $('#edit_id').val();
            let formData = new FormData(this);

            $.ajax({
                url: "<?= site_url('parking/update-ajax') ?>/" + id,
                type: "POST",
                data: formData,
                processData: false, // 🚨 REQUIRED
                contentType: false, // 🚨 REQUIRED
                success: function (res) {

                    let data = JSON.parse(res);

                    if (!data.status) {
                        $('#modalError').html(
                            '<div class="alert alert-danger">' + data.errors + '</div>'
                        );
                    } else {
                        location.reload();
                    }
                },
                error: function () {
                    alert('Something went wrong while updating');
                }
            });
        });

    });
</script>