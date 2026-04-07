<?php $this->load->view('layout/header', ['title' => 'Add Watchman']); ?>
<?php $old = $this->session->flashdata('old'); ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Add Watchman</h5>
                </div>

                <div class="card-body">

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post"
                          enctype="multipart/form-data"
                          action="<?= site_url('watchman/store') ?>">

                        <!-- Name & Email -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control"
                                       value="<?= $old['name'] ?? '' ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                       value="<?= $old['email'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- Password & Phone -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control"
                                       value="<?= $old['phone'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- DOB & Gender -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control"
                                       value="<?= $old['date_of_birth'] ?? '' ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male"   <?= ($old['gender'] ?? '')=='male'?'selected':'' ?>>Male</option>
                                    <option value="female" <?= ($old['gender'] ?? '')=='female'?'selected':'' ?>>Female</option>
                                    <option value="other"  <?= ($old['gender'] ?? '')=='other'?'selected':'' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Salary & Parking -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Salary</label>
                                <input type="number" name="salary" class="form-control"
                                       value="<?= $old['salary'] ?? '' ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Assign Parking</label>
                                <select name="parking_id" class="form-control">
                                    <option value="">Select Parking</option>
                                    <?php foreach ($parkings as $p): ?>
                                        <option value="<?= $p->park_id ?>"
                                            <?= ($old['parking_id'] ?? '')==$p->park_id?'selected':'' ?>>
                                            <?= $p->parking_name ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="mb-3">
                            <label>Watchman Image</label>
                            <input type="file" name="watchman_image"
                                   class="form-control" accept="image/*">
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('watchman') ?>" class="btn btn-secondary">Back</a>
                            <button type="submit" class="btn btn-success">Save Watchman</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>
