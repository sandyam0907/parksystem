<?php $this->load->view('layout/header',['title'=>'New Password']); ?>
<form method="post" action="<?=site_url('auth/save_new_password')?>" class="col-md-4 mx-auto">
<input type="password" name="password" class="form-control mb-3" placeholder="New Password" required>
<button class="btn btn-primary w-100">Update Password</button>
</form>
<?php $this->load->view('layout/footer'); ?>
