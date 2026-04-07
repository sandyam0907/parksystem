<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.min.css')?>">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-5">

<div class="card shadow">
<div class="card-header bg-primary-subtle border text-black text-center">Register</div>
<div class="card-body">

<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger"><?=$this->session->flashdata('error')?></div>
<?php endif; ?>

<form method="post" action="<?=site_url('register_save')?>">

<div class="mb-3">
<input type="text" name="name" class="form-control" placeholder="Name" required>
</div>

<div class="mb-3">
<input type="email" name="email" class="form-control" placeholder="Email" required>
</div>

<div class="mb-3">
<input type="password" name="password" class="form-control" placeholder="Password" required>
</div>

<div class="mb-3">
<select name="role_id" class="form-control" required>
<option value="">Select Role</option>
<option value="1">Admin</option>
<option value="2">Staff</option>
<option value="3">User</option>
</select>
</div>

<button class="btn btn-primary w-100">Register</button>

<a href="<?=site_url('login')?>" class="d-block text-center mt-3">Already have account? Login</a>

</form>
</div>
</div>

</div>
</div>
</div>
</body>
</html>
