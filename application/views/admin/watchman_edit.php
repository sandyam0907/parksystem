<?php $this->load->view('layout/header', ['title' => 'Edit Watchman']); ?>
<?php $old = $this->session->flashdata('old'); ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit Watchman</h5>
                </div>

                <div class="card-body">

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post"
                          enctype="multipart/form-data"
                          action="<?= site_url('watchman/update/'.$watchman->watchman_id) ?>">

                        <!-- Name & Email -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control"
                                       value="<?= $old['name'] ?? $watchman->name ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                       value="<?= $old['email'] ?? $watchman->email ?>">
                            </div>
                        </div>

                        <!-- Phone & Gender -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                       value="<?= $old['phone'] ?? $watchman->phone ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male"
                                        <?= (($old['gender'] ?? $watchman->gender) == 'male') ? 'selected' : '' ?>>
                                        Male
                                    </option>
                                    <option value="female"
                                        <?= (($old['gender'] ?? $watchman->gender) == 'female') ? 'selected' : '' ?>>
                                        Female
                                    </option>
                                    <option value="other"
                                        <?= (($old['gender'] ?? $watchman->gender) == 'other') ? 'selected' : '' ?>>
                                        Other
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- DOB & Salary -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control"
                                       value="<?= $old['date_of_birth'] ?? $watchman->date_of_birth ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Salary</label>
                                <input type="number" name="salary" class="form-control"
                                       value="<?= $old['salary'] ?? $watchman->salary ?>">
                            </div>
                        </div>

                        <!-- Parking -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Assign Parking</label>
                                <select name="parking_id" class="form-control">
                                    <option value="">Select Parking</option>
                                    <?php foreach ($parkings as $p): ?>
                                        <option value="<?= $p->park_id ?>"
                                            <?= (($old['parking_id'] ?? $watchman->parking_id) == $p->park_id)
                                                ? 'selected' : '' ?>>
                                            <?= $p->parking_name ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Watchman Image</label>
                                <input type="file" name="watchman_image"
                                       class="form-control" accept="image/*">

                                <?php if (!empty($watchman->watchman_image)): ?>
                                    <div class="mt-2">
                                        <img src="<?= base_url('uploads/watchman/'.$watchman->watchman_image) ?>"
                                             width="90" style="border-radius:4px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('watchman') ?>" class="btn btn-secondary">
                                Back
                            </a>
                            <button type="submit" class="btn btn-warning">
                                Update Watchman
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>
