<?php $this->load->view('layout/header',['title'=>'Forgot Password']); ?>

<form method="post" action="<?=site_url('auth/send_otp')?>" class="col-md-4 mx-auto">

<input type="email" name="email" class="form-control mb-3" placeholder="Enter your email" required>

<button class="btn btn-primary w-100">Send OTP</button>

</form>

<?php $this->load->view('layout/footer'); ?>
