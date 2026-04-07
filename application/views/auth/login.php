<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.min.css')?>">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-4">

<div class="card shadow">
<div class="card-header bg-primary-subtle border text-black text-center">Login</div>
<div class="card-body">

<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger"><?=$this->session->flashdata('error')?></div>
<?php endif; ?>

<form method="post" action="<?=site_url('login_check')?>">

<div class="mb-3">
<input type="email" name="email" class="form-control" placeholder="Email" required>
</div>

<div class="mb-3">
<input type="password" name="password" class="form-control" placeholder="Password" required>
</div>

<button class="btn btn-success w-100">Login</button>
<a href="<?=site_url('forgot')?>" class="d-block text-center mt-3 text-decoration-none">
Forgot Password?
</a>

<a href="<?=site_url('register')?>" class="d-block text-center mt-3">Back To Register</a>

</form>
</div>
</div>

</div>
</div>
</div>
</body>
</html>
