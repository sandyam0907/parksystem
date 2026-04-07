<?php $this->load->view('layout/header', ['title' => 'Watchman List']); ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Watchman List</h4>
        <a href="<?= site_url('watchman/add') ?>" class="btn btn-primary">+ Add Watchman</a>
    </div>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show w-75 mx-auto" role="alert">
        <?= $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

    <div class="table-responsive">
        <table id="myTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Salary</th>
                    <th>Parking</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($watchmen)): ?>
                    <?php foreach ($watchmen as $w): ?>
                        <tr>
                            <td><?= $w->watchman_id ?></td>

                            <td>
                                <?php if (!empty($w->watchman_image)): ?>
                                    <img src="<?= base_url('uploads/watchman/' . $w->watchman_image) ?>" width="50" height="40"
                                        style="object-fit:cover;border-radius:4px;">
                                <?php else: ?>
                                    <small>No Image</small>
                                <?php endif; ?>
                            </td>

                            <td><?= $w->name ?></td>
                            <td><?= $w->email ?></td>
                            <td><?= $w->phone ?></td>
                            <td><?= ucfirst($w->gender) ?></td>
                            <td>₹ <?= $w->salary ?></td>
                            <td><?= $w->parking_name ?? '-' ?></td>

                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input watchman-status-switch" type="checkbox"
                                        data-id="<?= $w->watchman_id ?>" <?= $w->status === 'active' ? 'checked' : '' ?>>

                                    <label class="form-check-label">
                                        <span class="status-text">
                                            <?= ucfirst($w->status) ?>
                                        </span>
                                    </label>
                                </div>
                            </td>

                            <td>
                                <a href="<?= site_url('watchman/edit/' . $w->watchman_id) ?>"
                                    class="btn btn-sm btn-warning">Edit</a>

                                <a href="<?= site_url('watchman/delete/' . $w->watchman_id) ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this watchman?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">No watchman records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>
<script>
    $(document).on('change', '.watchman-status-switch', function () {

        let checkbox = $(this);
        let watchmanId = checkbox.data('id');
        let newStatus = checkbox.is(':checked') ? 'active' : 'inactive';

        $.ajax({
            url: "<?= site_url('watchman/update_status') ?>",
            type: "POST",
            data: {
                watchman_id: watchmanId,
                status: newStatus
            },
            success: function (res) {
                let r = JSON.parse(res);

                if (r.status) {
                    // update text instantly
                    checkbox
                        .closest('.form-check')
                        .find('.status-text')
                        .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                } else {
                    alert('Status update failed');
                    checkbox.prop('checked', !checkbox.is(':checked')); // rollback
                }
            },
            error: function () {
                alert('Something went wrong');
                checkbox.prop('checked', !checkbox.is(':checked')); // rollback
            }
        });
    });

</script>