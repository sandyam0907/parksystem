<!DOCTYPE html>
<html>

<head>
   <title><?= $title ?? 'Parking System' ?></title>

   <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
   <link rel="stylesheet" href="<?= base_url('assets/css/datatables.min.css') ?>">
   <!-- <link rel="stylesheet" href="<?= base_url('assets/css/buttons.dataTables.min.css') ?>"> -->

   <style>
      .navbar {
         min-height: 50px;
         padding: 4px 16px;
      }

      .profile-img {
         width: 34px;
         height: 34px;
         border-radius: 50%;
      }

      html,
      body {
         height: 100%;
      }

      body {
         display: flex;
         flex-direction: column;
      }

      .page-wrapper {
         flex: 1;
         display: flex;
      }
   </style>
</head>

<body>

   <?php
   $CI =& get_instance();
   $CI->load->model('UserModel');
   $user = $CI->UserModel->getUserById($CI->session->userdata('uid'));
   $hasPhoto = (!empty($user) && !empty($user->photo));
   $photo = $hasPhoto ? base_url('uploads/' . $user->photo) : '';
   ?>

   <nav class="navbar" style="background-color:#06606aff;">
      <a class="navbar-brand text-white"
         href="<?= site_url($user->role_id == 1 ? 'admin' : ($user->role_id == 2 ? 'staff' : 'user')) ?>">
         Parking System</a>

      <div class="ms-auto dropdown">
         <a class="text-white dropdown-toggle d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown"
            href="#">
            <?php if ($hasPhoto): ?>
               <img src="<?= $photo ?>" class="profile-img">
            <?php else: ?>
               <span><?= $user->name ?></span>
            <?php endif; ?>
         </a>

         <ul class="dropdown-menu dropdown-menu-end p-2">
            <li class="text-center mb-2">
               <?php if ($hasPhoto): ?>
                  <img src="<?= $photo ?>" width="70" height="70" class="rounded-circle mb-2">
               <?php endif; ?>
               <div class="fw-bold"><?= $user->name ?></div>
               <small class="text-muted"><?= $user->role_name ?></small>
            </li>
            <li>
               <hr>
            </li>
            <li><a class="dropdown-item"
                  href="<?= site_url(($user->role_id == 1 ? 'admin' : ($user->role_id == 2 ? 'staff' : 'user')) . '/profile') ?>">Edit
                  Profile</a>
            </li>
            <li><a class="dropdown-item text-danger" href="<?= site_url('logout') ?>">Logout</a></li>
         </ul>
      </div>
   </nav>

   <div class="page-wrapper">
      <?php $this->load->view('layout/sidebar'); ?>
      <div class="flex-fill p-3" style="overflow-y:auto">