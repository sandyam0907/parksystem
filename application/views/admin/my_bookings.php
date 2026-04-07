<?php $this->load->view('layout/header', ['title' => 'Bookings List']); ?>

<h4>Parking Bookings</h4>

<?php if (!empty($bookings)): ?>
<div class="table-responsive">
<table id="myTable" class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Booking Ref</th>
            <th>Booked By</th>
            <th>Username</th>
            <th>Booked Date</th>
            <th>Created At</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
            <td><?= $b->booking_ref ?></td>
            <td><?= $b->name ?></td>
            <td><?= $b->username ?></td>
            <td><?= date('d-m-Y', strtotime($b->booked_date)) ?></td>
            <td><?= date('d-m-Y h:i A', strtotime($b->created_at)) ?></td>
            <td>
                <span class="badge bg-<?= $b->booking_status == 'Confirmed' ? 'success' : 'secondary' ?>">
                    <?= $b->booking_status ?>
                </span>
            </td>
            <td>
                <a href="<?= site_url('bookings/view/'.$b->booking_id) ?>"
                   class="btn btn-sm btn-info">
                   View
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php else: ?>
    <div class="alert alert-info">No bookings found</div>
<?php endif; ?>

<?php $this->load->view('layout/footer'); ?>
