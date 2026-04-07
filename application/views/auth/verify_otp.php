<?php $this->load->view('layout/header',['title'=>'Verify OTP']); ?>

<div class="row justify-content-center">
<div class="col-md-4">

<div class="card shadow text-center">
<div class="card-header bg-primary-subtle border text-black">Verify OTP</div>
<div class="card-body">

<?php if(isset($expired) && $expired): ?>

<div class="alert alert-danger">OTP expired. Please resend.</div>

<a href="<?=site_url('forgot')?>" class="btn btn-warning w-100">Resend OTP</a>

<?php else: ?>

<div id="timer" class="alert alert-warning fw-bold"></div>

<form method="post" action="<?=site_url('auth/check_otp')?>">

<div class="mb-3">
<input name="otp" class="form-control" placeholder="Enter OTP" required>
</div>

<button class="btn btn-success w-100">Verify OTP</button>
</form>

<?php endif; ?>

</div>
</div>

</div>
</div>

<?php if(!isset($expired) || !$expired): ?>
<script>
let expire = <?=$expiry?> * 1000;

function startTimer(){
    let now = new Date().getTime();
    let diff = expire - now;

    if(diff <= 0){
        location.reload(); // reload page to auto switch to resend
        return;
    }

    let min = Math.floor(diff / (1000*60));
    let sec = Math.floor((diff % (1000*60))/1000);

    document.getElementById('timer').innerHTML =
      "OTP expires in: " + min + "m " + sec + "s";

    setTimeout(startTimer,1000);
}
startTimer();
</script>
<?php endif; ?>

<?php $this->load->view('layout/footer'); ?>
