<?php $this->load->view('layout/header',['title'=>'My Profile']); ?>

<div class="row justify-content-center">
<div class="col-md-5">

<div class="card shadow">
<div class="card-header text-black text-center "style="background-color: #76c8d1ff;" >Edit Profile</div>
<div class="card-body">
<form method="post" enctype="multipart/form-data" action="<?=site_url('profile/update')?>">

<div class="text-center mb-3">

<?php if(!empty($user->photo)): ?>
<img src="<?=base_url('uploads/'.$user->photo)?>" width="120" height="120"
     class="rounded-circle mb-2"><br>

<a href="<?=site_url('profile/remove_photo')?>" 
   class="btn btn-sm btn-outline-warning mb-2">
Remove
</a>

<?php else: ?>
<div class="border rounded-circle d-flex align-items-center justify-content-center mb-2"
     style="width:120px;height:120px;font-size:40px">
<?=strtoupper(substr($user->name,0,1))?>
</div>
<?php endif; ?>

</div>


<input type="file" name="photo" class="form-control mb-3">

<input name="name" value="<?=$user->name?>" class="form-control mb-2" placeholder="Name">

<input name="email" value="<?=$user->email?>" class="form-control mb-3" placeholder="Email">

<button class="btn  w-100" style="background-color: #76d1a1ff;">Update Profile</button>
</form>

</div>
</div>

</div>
</div>

<?php $this->load->view('layout/footer'); ?>
